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
use yii\helpers\Html;

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

            $name = $_POST['name'];
            $permission = $_POST['permission'];

            $group->name = $name;
            $group->permission = $permission;
            $group->user_id = $currentUserId;
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
        $groups = Group::find()->all();
        $data = array();
        foreach ($groups as $group) {
            $data[] = array(
                'id' => $group->id,
                //'city_id'=>$value->city_id,
                'name' => $group->name,
                'permission' => $group->permission,
                'created_at' => date("M d, Y", strtotime($group->created_at)),
                'users' => UserGroup::find()->where(array("group_id" => $group->id))->count(),
                'owner' => (Yii::$app->user->id == $group->user_id ? true : false),
            );
        }
        return json_encode(array('data'=> $data ,'city' => ($cty ? $cty->zip_code : $zipcode)));
    }
}