<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Feedback;
use frontend\modules\netwrk\models\FeedbackStat;
use yii\db\Query;
use Yii;

class FeedbackController extends BaseController
{
    public function actionPost()
    {
        $returnData = array();

        $object = isset($_POST['object']) ? $_POST['object'] : '';
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $option = isset($_POST['option']) ? $_POST['option'] : '';
        $point = isset($_POST['point']) ? $_POST['point'] : '';
        $currentUser = Yii::$app->user->id;

        $feedback_posted = Feedback::isFeedbackPostedByUser($object,$id);

        if(!$feedback_posted)
        {
            $feedback = new Feedback;

            $feedback->feedback = $option;
            $feedback->point = $point;
            $feedback->user_id = $currentUser;
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

            $returnData['feedbackPoints'] = $feedback_points;
        }

        $returnData['success'] = 'true';

        return json_encode($returnData);
    }
}