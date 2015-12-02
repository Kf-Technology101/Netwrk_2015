<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\UserMeet;
use frontend\modules\netwrk\models\UserSettings;
use yii\helpers\Url;

class UserController extends BaseController
{

    public function actionIndex(){
        return $this->render($this->getIsMobile() ? 'mobile/login' : '');
    }

    public function actionRegister(){
        return $this->render($this->getIsMobile() ? 'mobile/register' : '');
    }
}