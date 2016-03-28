<?php
namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use yii\helpers\Url;

class PasswordSettingController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('mobile/index');
    }
}

?>