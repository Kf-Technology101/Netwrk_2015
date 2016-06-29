<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Feedback;
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

        $returnData['success'] = 'true';

        return json_encode($returnData);
    }
}