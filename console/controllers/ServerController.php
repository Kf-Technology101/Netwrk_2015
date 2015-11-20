<?php
namespace console\controllers;

use Yii;
use frontend\components\UtilitiesFunc;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use frontend\modules\netwrk\controllers\ChatServer;

class ServerController extends \yii\console\Controller
{
	public function actionShutdown()
	{
		file_put_contents(Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt", "0");
	}

	public function actionRun()
	{
		$startNow = 1;
		// register_shutdown_function('actionShutdown');

		if( isset($startNow) ){
			$server = IoServer::factory(
				new HttpServer(
					new WsServer(
						new ChatServer()
						)
					),
				8080,
				"127.0.0.1"
				);
			$server->run();
		}
	}
}