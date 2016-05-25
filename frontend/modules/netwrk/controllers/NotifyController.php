<?php
namespace frontend\modules\netwrk\controllers;

use frontend\modules\netwrk\models\ChatDiscussion;
use Yii;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Notification;
use yii\db\ActiveQuery;
use yii\helpers\Url;

class NotifyController extends BaseController
{
    /**
     * Count unread chat message
     */
    public function actionCountUnreadMessage(){
        $currentUser = Yii::$app->user->id;
        $data = Notification::find()->select('sender')->where(['receiver'=>$currentUser, 'status'=>0])->distinct()->count();
        $hash = json_encode($data);
        return $hash;
    }

     /**
     * Change unread chat message is notified
     */
    public function actionUpdateChatShowStatus(){
        $currentUser = Yii::$app->user->id;
        $data = Notification::find()->where(['receiver'=>$currentUser, 'status'=>0, 'chat_show'=>0])->all();
        // echo '<pre>';print_r($data);
        for ($i=0; $i < count($data); $i++) {
            $notify = Notification::findOne($data[$i]->id);
            $notify->chat_show = 1;
            $notify->update();
        }
    }

    /**
     * Count unread chat message from user
     */
    public function actionCountUnreadMsgFromUser(){
        $sender = $_POST['sender'];
        $currentUser = Yii::$app->user->id;
        $data = Notification::find()->where(['sender'=>$sender, 'receiver'=>$currentUser, 'status'=>0])->count();
        $hash = json_encode($data);
        return $hash;
    }

    /**
     * Reset count unread chat message from user
     */
    public function actionChangeStatusUnreadMsg(){
        $sender = $_POST['sender'];
        $currentUser = Yii::$app->user->id;
        $data = Notification::find()->where(['sender'=>$sender, 'receiver'=>$currentUser, 'status'=>0])->all();
        for ($i=0; $i < count($data); $i++) {
            $notify = Notification::findOne($data[$i]->id);
            $notify->status = 1;
            $notify->update();
        }
    }

    /**
     * Reset count unread chat message from user
     */
    public function actionChangeStatusUnreadDiscussionMsg(){
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';

        if($user_id && $post_id) {
            $data = ChatDiscussion::find()->where(['user_id'=>$user_id, 'post_id'=>$post_id, 'notification_count' => 1])->all();
            for ($i=0; $i < count($data); $i++) {
                $notify = ChatDiscussion::findOne($data[$i]->id);
                $notify->notification_count = 0;
                $notify->update();
            }
        }
    }
}