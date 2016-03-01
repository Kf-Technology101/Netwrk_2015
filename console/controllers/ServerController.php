<?php
namespace console\controllers;

use Yii;
use frontend\components\UtilitiesFunc;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use frontend\modules\netwrk\controllers\ChatServer;
use frontend\modules\netwrk\controllers\ChatController;

class ServerController extends \yii\console\Controller
{
	public function actionShutdown()
	{
		file_put_contents(Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt", "0");
		ChatController::actionExecInbg("php yii server/run");
	}

	public function actionRun()
	{
		$startNow = 1;
		// register_shutdown_function(array($this, 'actionShutdown'));
		if( isset($startNow) ){
			$server = IoServer::factory(
				new HttpServer(
					new WsServer(
						new ChatServer()
						)
					),2311);
			$server->run();
		}
	}
}