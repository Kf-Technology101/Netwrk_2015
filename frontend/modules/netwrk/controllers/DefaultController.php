<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\City;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render($this->getIsMobile() ? 'mobile/index' : 'index', [
            'cities' => City::find()->all()
        ]);
    }
}
