<?php
namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use yii\helpers\Url;
use frontend\modules\netwrk\controllers\SettingController;

class SearchSettingController extends BaseController
{
    public function actionIndex()
    {
        $data = json_decode(SettingController::actionGetUserSetting());
        return $this->render('mobile/index', $data);
    }
}

?>