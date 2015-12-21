<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\User;
use yii\helpers\Url;

class ChatPrivateController extends BaseController
{
	public function actionExecInbg($cmd)
	{
		if (substr(php_uname(), 0, 7) == "Windows"){
			pclose(popen("start /B ". $cmd, "r"));
		} else {
			exec($cmd . " > /dev/null &");
		}
	}

	public function actionIndex()
	{
		$user_id = $_GET['privateId'];

		$user = User::find()->where('id = '. $user_id)->with('profile')->one();

		$statusFile = Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt";
		$status = file_get_contents($statusFile);
		if($status == 0){
			/* This means, the WebSocket server is not started. So we, start it */
			$this->actionExecInbg("php yii server/run");
			file_put_contents($statusFile, 1);
		}

		return $this->render($this->getIsMobile() ? 'mobile/index' : '' , ['user'=> $user] );

	}

}