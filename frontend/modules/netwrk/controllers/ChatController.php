<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;

class ChatController extends BaseController
{
    public function actionIndex()
    {
    	return $this->render('chat');
    }
}