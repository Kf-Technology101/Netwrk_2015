<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use yii\helpers\Url;
use frontend\modules\netwrk\models\PreviousPage;

class PreviousPageController extends BaseController
{

	public function actionSetPreviousPage()
	{
		$previous = new PreviousPage();
		$previous = $previous->find()->where('id = 1')->one();
		$previous->url =  isset($_GET['previous']) ? $_GET['previous'] : Url::base(true);
		$previous->save(false);
		return true;
	}

	public function actionGetPreviousPage()
	{
		$previous = new PreviousPage();
		$previous = $previous->find()->where('id = 1')->one();
		return $previous->url;
	}

}