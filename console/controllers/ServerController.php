<?php
namespace console\controllers;

use Yii;
use frontend\components\UtilitiesFunc;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use frontend\modules\netwrk\controllers\ChatServer;
use frontend\modules\netwrk\controllers\ChatController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class ServerController extends \yii\console\Controller
{

	public function actionRun()
	{
		$server = IoServer::factory(
			new HttpServer(
				new WsServer(
					new ChatServer()
					)
				)
				,2311);
		$server->run();
	}
}
