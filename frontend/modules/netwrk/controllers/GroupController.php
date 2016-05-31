<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Group;
use frontend\modules\netwrk\models\Role;
use frontend\modules\netwrk\models\UserGroup;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\UserInvitation;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\Post;
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
                $emails = array_map('strtolower', array_unique($_POST['emails']));
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

            if (!empty($_POST['byGroup']) && $_POST['byGroup'] != "false") {
                $group->latitude = doubleval($_POST['latitude']);
                $group->longitude = doubleval($_POST['longitude']);
            } else {
                $group->city_id = intval($_POST['city_id']);
            }

            $group->save();

            UserGroup::deleteAll(array("group_id" => $group->id));

            if (!empty($_POST['emails'])) {
                foreach ($emails as $email) {
                    if (!array_key_exists($email, $existingEmails)) {
                        //creating incomplete user
                        $username = preg_replace('/[^A-Za-z0-9\-]/', '',preg_replace('/([^@]*).*/', '$1', $email));

                        $user = new User();
                        $user->email = $email;
                        $user->username = $username;
                        $user->status = User::STATUS_INCOMPLETE;
                        $user->role_id = Role::ROLE_USER;
                        $user->save();

                        //sending invitation
                        $invitation = new UserInvitation();
                        $invitation->user_id = $user->id;
                        $invitation->user_from = $currentUserId;
                        $invitation->invitation_code = Yii::$app->security->generateRandomString();
                        $invitation->save();

                        $status = UserGroup::STATUS_INVITED;
                    } else {
                        $user = $existingEmails[$email];
                        $status = UserGroup::STATUS_JOINED;
                    }
                    $userGroup = new UserGroup();
                    $userGroup->user_id = $user->id;
                    $userGroup->group_id = $group->id;
                    $userGroup->status = $status;
                    $userGroup->save();
                }
            }

            $transaction->commit();

            // If new group created add general topic under this new group
            if (empty($_POST['id'])) {
                $Topic = new Topic;
                $Topic->group_id = $group->id;
                $Topic->user_id = $currentUserId;
                $Topic->title = $name;
                $Topic->save();

                $Post = new Post();
                $Post->title = 'groupchat';
                $Post->content = 'Join the party!';
                $Post->topic_id = $Topic->id;
                $Post->user_id = $currentUserId;
                $Post->post_type = 1;
                $Post->save();

                $Topic->post_count = 1;
                $Topic->update();
            }

            die(json_encode(array("error" => false, "group_id" => $group->id)));

        } catch (Exception $e) {
            $transaction->rollBack();
            die(json_encode(array("error" => true, "message" => $e->getMessage())));
        }
    }

    public function actionDeleteGroup() {

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $currentUserId = Yii::$app->user->id;
            $currentUser = User::find()->where(array("id" => $currentUserId))->one();

            if (empty($currentUser)) {
                throw new Exception("Unknown error, please try to re-login");
            }

            if (empty($_POST['id'])) throw new Exception("Nothing to delete");
            $group = Group::findOne($_POST['id']);

            if (empty($group) || $group->user_id != $currentUserId) {
                throw new Exception("Unknown group or user");
            }

            $group->delete();

            $transaction->commit();

            die(json_encode(array("error" => false)));

        } catch (Exception $e) {
            $transaction->rollBack();
            die(json_encode(array("error" => true, "message" => $e->getMessage())));
        }
    }

    public function actionGetGroups() {
        $filter = $_GET['filter'];
        switch ($filter) {
            case 'post':
                $order = ['post_count'=> SORT_DESC];
                break;
            case 'view':
                $order = ['view_count'=> SORT_DESC];
                break;
            case 'recent':
            default:
                $order = ['created_at'=> SORT_DESC];
                break;
        }
        $params = array();
        if (isset($_GET['city'])) {
            $city = $_GET['city'];
            $cty = City::findOne($city);
            if (!$cty) {
                $zipcode = $_GET['zipcode'];
            }
            $params['group.city_id'] = $cty->id;
        }
        if (isset($_GET['group_id'])) {
            $params['id'] = $_GET['group_id'];
        }

        $andWhere = 'permission = ' . Group::PERMISSION_PUBLIC;

        if(Yii::$app->user->id){
            $currentUserId = Yii::$app->user->id;
            $andWhere = $andWhere. ' or group.user_id = ' . $currentUserId . " or " . $currentUserId . " in (select user_id from user_group where user_group.group_id = group.id)";
        }else{
            $currentUserId = null;
        }

        $groups = Group::find()
            ->where($params)
            ->andWhere($andWhere)
            ->joinWith("topic")
            ->orderBy($order)
            ->all();
        $data = array();
        foreach ($groups as $group) {
            $num_date = UtilitiesFunc::FormatDateTime($group->created_at);
            $data[] = array(
                'id' => $group->id,
                //'city_id'=>$value->city_id,
                'name' => $group->name,
                'permission' => $group->permission,
                'created_at' => $num_date, //date("M d, Y", strtotime($group->created_at)),
                'users' => UserGroup::find()->where(array("group_id" => $group->id))->count(),
                'owner' => ($currentUserId == $group->user_id ? true : false),
            );
        }
        $res = array('data'=> $data);
        if (!empty($_GET['city'])) $res['city'] = ($cty ? $cty->zip_code : $zipcode);
        return json_encode($res);
    }

    public function actionGetGroup() {
        $currentUserId = Yii::$app->user->id;
        if (empty($_POST['id'])) return json_encode(array('error' => true));
        /** @var Group $group */
        $group = Group::find()->where(array("user_id" => $currentUserId, "id" => intval($_POST['id'])))->one();
        if (empty($group)) return json_encode(array('error' => true));
        $data = $group->toArray();
        $data['users'] = array_values(UserGroup::find()->joinWith("user")->where(array("group_id" => $group->id))->asArray()->all());
        $data['error'] = false;
        return json_encode($data);
    }

    public function actionGetUsers() {
        try {
            $currentUserId = Yii::$app->user->id;
            $currentUser = User::find()->where(array("id" => $currentUserId))->one();

            if (empty($currentUser)) {
                throw new Exception("Unknown error, please try to re-login");
            }

            if (empty($_POST['id'])) throw new Exception("Nothing to delete");
            $group = Group::findOne($_POST['id']);

            if (empty($group) || $group->user_id != $currentUserId) {
                throw new Exception("Unknown group or user");
            }

            $users = UserGroup::find()->where(array("group_id" => $group->id))->joinWith('user')->all();
            $data = array();
            foreach ($users as $user) {
                $status = ($user['status'] == UserGroup::STATUS_JOINED ? "joined" : "invited");
                if (!isset($data[$status])) $data[$status] = array();
                $data[$status][] = array(
                    "id" => $user->user->id,
                    "name" => $user->user->username,
                    "email" => $user->user->email,
                );
            }

            foreach ($data as $s => $status) {
                $data[$s] = array_values($status);
            }

            die(json_encode($data));

        } catch (Exception $e) {
            die(json_encode(array("error" => true, "message" => $e->getMessage())));
        }
    }

    public function actionGetGroupsByUser()
    {
        $filter = $_GET['filter'];
        $currentUserId = isset($_GET['user']) ? $_GET['user'] : Yii::$app->user->id;
        switch ($filter) {
            case 'post':
                $order = ['post_count' => SORT_DESC];
                break;
            case 'view':
                $order = ['view_count' => SORT_DESC];
                break;
            case 'recent':
            default:
                $order = ['created_at' => SORT_DESC];
                break;
        }
        $params = array();
        if (isset($_GET['city'])) {
            $city = $_GET['city'];
            $cty = City::findOne($city);
            if (!$cty) {
                $zipcode = $_GET['zipcode'];
            }
            $params['group.city_id'] = $cty->id;
        }
        if (isset($_GET['group_id'])) {
            $params['id'] = $_GET['group_id'];
        }

        //get current users groups
        $params['user_id'] = $currentUserId;

        $groups = Group::find()
            ->where($params)
            ->orderBy($order);

        $totalCount = $groups->count();

        $groups = $groups->all();

        /*$sql = $groups->createCommand()->getRawSql();
        echo($sql);
        die();*/

        $data = array();
        foreach ($groups as $group) {
            $num_date = UtilitiesFunc::FormatDateTime($group->created_at);
            $data[] = array(
                'id' => $group->id,
                'name' => $group->name,
                'permission' => $group->permission,
                'city_id' => $group->city_id,
                'created_at' => date("M d, Y", strtotime($group->created_at)),
                'formatted_created_at' => date("M d", strtotime($group->created_at)),
                'formatted_created_date' => date('M d', strtotime($group->created_at)),
                'formatted_created_date_month_year' => date('F Y', strtotime($group->created_at)),
                'users' => UserGroup::find()->where(array("group_id" => $group->id))->count(),
                'owner' => ($currentUserId == $group->user_id ? true : false),
            );
        }

        //Grouped activity in month
        $groupArray = array();
        foreach ($data as $item) {
            $groupArray[$item['formatted_created_date_month_year']][] = $item;
        }
        //var_dump($groupArray);

        $temp = array('data' => $groupArray, 'total_count' => $totalCount);

        $hash = json_encode($temp);
        return $hash;
    }

}