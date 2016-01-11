<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\Group;
use frontend\modules\netwrk\models\Role;
use frontend\modules\netwrk\models\UserGroup;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\UserInvitation;
use frontend\modules\netwrk\models\City;
use yii\base\Exception;
use Yii;

class GroupController extends BaseController {
    public function actionCreateEditGroup() {

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $currentUserId = Yii::$app->user->id;
            $currentUser = User::find()->where(array("id" => $currentUserId))->one();

            if (empty($currentUser)) {
                throw new Exception("Unknown error, please try to re-login");
            }

            if (!empty($_POST['emails'])) {
                $emails = array_unique($_POST['emails']);
                foreach ($emails as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid email(s)");
                    }
                    if ($email == $currentUser->email) {
                        throw new Exception("You cannot add yourself");
                    }
                }
                $users = User::find()
                    ->where(array("email" => $emails))
                    ->all();
                $existingEmails = array();
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $existingEmails[$user->email] = $user;
                    }
                }
            }

            if (!empty($_POST['id'])) {
                $group = Group::find()->where(array("id" => $_POST['id']))->one();
                if (empty($group) || $group->user_id != $currentUserId) {
                    throw new Exception("Unknown group or user");
                }
            } else {
                $group = new Group();
            }

            //todo: validate
            $name = $_POST['name'];
            $permission = $_POST['permission'];

            $group->name = $name;
            $group->permission = $permission;
            $group->user_id = $currentUserId;
            $group->city_id = intval($_POST['city_id']);
            $group->save();

            UserGroup::deleteAll(array("group_id" => $group->id));

            if (!empty($_POST['emails'])) {
                foreach ($emails as $email) {
                    if (!array_key_exists($email, $existingEmails)) {
                        //creating incomplete user
                        $user = new User();
                        $user->email = $email;
                        $user->status = User::STATUS_INCOMPLETE;
                        $user->role_id = Role::ROLE_USER;
                        $user->save();
                        //sending invitation
                        $invitation = new UserInvitation();
                        $invitation->user_id = $user->id;
                        $invitation->user_from = $currentUserId;
                        $invitation->invitation_code = Yii::$app->security->generateRandomString();

                    } else {
                        $user = $existingEmails[$email];
                    }
                    $userGroup = new UserGroup();
                    $userGroup->user_id = $user->id;
                    $userGroup->group_id = $group->id;
                }
            }

            $transaction->commit();

            die(json_encode(array("error" => false)));

        } catch (Exception $e) {
            $transaction->rollBack();
            die(json_encode(array("error" => true, "message" => $e->getMessage())));
        }
    }

    public function actionGetGroups() {
        $city = $_GET['city'];
        $cty = City::findOne($city);
        if(!$cty){
            $zipcode = $_GET['zipcode'];
        }
        $currentUserId = Yii::$app->user->id;
        $groups = Group::find()
            ->where(array("city_id" => $cty->id))
            ->andWhere('permission = ' . UserGroup::PERMISSION_PUBLIC . ' or user_id = ' . $currentUserId . " or " . $currentUserId . " in (select user_id from user_group where group_id = group.id)")
            ->all();
        $data = array();
        foreach ($groups as $group) {
            $data[] = array(
                'id' => $group->id,
                //'city_id'=>$value->city_id,
                'name' => $group->name,
                'permission' => $group->permission,
                'created_at' => date("M d, Y", strtotime($group->created_at)),
                'users' => UserGroup::find()->where(array("group_id" => $group->id))->count(),
                'owner' => ($currentUserId == $group->user_id ? true : false),
            );
        }
        return json_encode(array('data'=> $data ,'city' => ($cty ? $cty->zip_code : $zipcode)));
    }

    public function actionGetGroup() {
        $currentUserId = Yii::$app->user->id;
        if (empty($_POST['id'])) return json_encode(array('error' => true));
        /** @var Group $group */
        $group = Group::find()->where(array("user_id" => $currentUserId, "id" => intval($_POST['id'])))->one();
        if (empty($group)) return json_encode(array('error' => true));
        $data = $group->toArray();
        $data['users'] = array_values(UserGroup::find()->where(array("group_id" => $group->id))->asArray()->all());
        $data['error'] = false;
        return json_encode($data);
    }
}