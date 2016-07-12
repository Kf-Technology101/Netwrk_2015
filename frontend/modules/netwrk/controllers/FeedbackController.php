<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Feedback;
use frontend\modules\netwrk\models\FeedbackStat;
use yii\db\Query;
use Yii;

class FeedbackController extends BaseController
{
    public function actionPost()
    {
        $returnData = array();

        $add_feedback = true;
        $object = isset($_POST['object']) ? $_POST['object'] : 'ws_message';
        $id = isset($_POST['id']) ? $_POST['id'] : '49320';
        $option = isset($_POST['option']) ? $_POST['option'] : 'like';
        $point = isset($_POST['point']) ? $_POST['point'] : '';
        $currentUserId = Yii::$app->user->id;
        $currentUser = User::find()->where(array("id" => $currentUserId))->one();
        $timeless_count = $currentUser->timeless_count;

        $feedback_posted = Feedback::isFeedbackPostedByUser($object,$id);

        if(!$feedback_posted)
        {
            // Check if feedback is like then check for users timeless count
            if($option == 'like' && $timeless_count >= 3){
                $add_feedback = false;
                $returnData['msgClass'] = 'alert-info';
                $returnData['msg'] = 'You have 0 left for this month';
                $returnData['success'] = 'true';

                return json_encode($returnData);
            }

            if($add_feedback) {
                $feedback = new Feedback;

                $feedback->feedback = $option;
                $feedback->point = $point;
                $feedback->user_id = $currentUserId;
                if ($object == 'ws_message') {
                    $feedback->ws_message_id = $id;
                }
                $feedback->type = $object;
                $feedback->created_at = date('Y-m-d H:i:s');
                $feedback->save();

                $feedback_points = Feedback::countFeedbackPoints($object,$id);

                $is_feedback_stat = FeedbackStat::isFeedbackStat($object,$id);

                if($is_feedback_stat > 0) {
                    $feedback_stat = FeedbackStat::find()->where(array("id" => $is_feedback_stat))->one();
                } else {
                    $feedback_stat = new FeedbackStat;
                }

                $feedback_stat->points = $feedback_points;
                if ($object == 'ws_message')
                {
                    $feedback_stat->ws_message_id = $id;
                }
                $feedback_stat->type = $object;
                $feedback_stat->save();

                if($option == 'like') {
                    $currentUser->timeless_count = $currentUser->timeless_count + 1;
                    $currentUser->save();

                    $remain = 3 - $currentUser->timeless_count;

                    $returnData['msgClass'] = 'alert-info';
                    $returnData['msg'] = 'You have '.$remain.' left for this month';
                } else {
                    $returnData['msgClass'] = 'alert-success';
                    $returnData['msg'] = 'Feedback added successfully';
                }

                $returnData['feedbackPoints'] = $feedback_points;
            }
        } else {
            $feedback_points = Feedback::countFeedbackPoints($object,$id);

            $returnData['feedbackPoints'] = $feedback_points;
            $returnData['msgClass'] = 'alert-danger';
            $returnData['msg'] = 'You already given feedback for this';
        }

        $returnData['success'] = 'true';

        return json_encode($returnData);
    }
}