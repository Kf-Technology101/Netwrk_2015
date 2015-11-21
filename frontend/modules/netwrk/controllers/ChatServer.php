<?php
namespace frontend\modules\netwrk\controllers;

use Ratchet\MessageComponentInterface;
use frontend\components\BaseController;
use Ratchet\ConnectionInterface;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\WsMessages;


class ChatServer extends BaseController implements MessageComponentInterface {
	protected $clients;
	protected $ws_messages;
	private $users = array();
	public function __construct()
	{
		$this->ws_messages = new WsMessages();
        $this->clients 	= new \SplObjectStorage;
    }

	public function onOpen(ConnectionInterface $conn)
	{
		$this->clients->attach($conn);
		$this->send($conn, "fetch", $this->fetchMessages());
		// $this->checkOnliners();
		echo "New connection! ({$conn->resourceId})\n";
	}

	public function onMessage(ConnectionInterface $from, $data)
	{
		$id	  = $from->resourceId;
		$data = json_decode($data, true);
		if(isset($data['data']) && count($data['data']) != 0){
			$type = $data['type'];
			$user = isset($this->users[$id]) ? $this->users[$id]['name'] : false;
			if($type == "register"){
				$name = htmlspecialchars($data['data']['name']);
				$this->users[$id] = array(
					"name" 	=> $name,
					"seen"	=> time()
				);
			}elseif($type == "send" && $user !== false){
				$msg = htmlspecialchars($data['data']['msg']);
				$data = [
					'user_id' => $user,
					'msg' => $msg,
					'post_id' => 1,
					'post_type' => 0,
					'msg_type' => 0
				];
				if($ws_messages->load())

				$sql = $ws_messages->insert("INSERT INTO `ws_messages` (`user_id`, `msg`, `created_at`) VALUES(?, ?, NOW())");
				$sql->execute(array($user, $msg));
				foreach ($this->clients as $client) {
					$this->send($client, "single", array("user_id" => $user, "msg" => $msg, "created_at" => date("Y-m-d H:i:s")));
				}
			}elseif($type == "fetch"){
				$this->send($from, "fetch", $this->fetchMessages());
			}
		}
		$this->checkOnliners($from);
	}

	public function onClose(ConnectionInterface $conn)
	{
		if( isset($this->users[$conn->resourceId]) ){
			unset($this->users[$conn->resourceId]);
		}
		$this->clients->detach($conn);
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		$conn->close();
	}

	/* My custom functions */
	public function fetchMessages()
	{
		$msgs = $this->ws_messages->find()->all();
		return $msgs;
	}

	public function checkOnliners($curUser = "")
	{
		date_default_timezone_set("UTC");
		if( $curUser != "" && isset($this->users[$curUser->resourceId]) ){
			$this->users[$curUser->resourceId]['seen'] = time();
		}

		$curtime 	= strtotime(date("Y-m-d H:i:s", strtotime('-5 seconds', time())));
		foreach($this->users as $id => $user){
			$usertime 	= $user['seen'];
			if($usertime < $curtime){
				unset($this->users[$id]);
			}
		}

		/* Send online users to evryone */
		$data = $this->users;
		foreach ($this->clients as $client) {
			$this->send($client, "onliners", $data);
		}
	}

	public function send($client, $type, $data)
	{
		$send = array(
			"type" => $type,
			"data" => $data
		);
		$send = json_encode($send, true);
		$client->send($send);
	}
}
?>