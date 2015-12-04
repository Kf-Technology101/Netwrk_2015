<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use Ratchet\MessageComponentInterface;
use frontend\components\BaseController;
use Ratchet\ConnectionInterface;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\WsMessages;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class ChatServer extends BaseController implements MessageComponentInterface {

	protected $clients;
	protected $ws_messages;
	protected $current_user = 1;
	protected $post_id = 0;
	private $users = array();

	public function __construct()
	{
		$this->ws_messages = new WsMessages();
		$this->clients 	= new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn)
	{
		$this->post_id = $conn->WebSocket->request->getQuery()->get('post');
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
			// $user = $current_user;
			if($type == "register"){
				$name = htmlspecialchars($data['data']['name']);
				$this->users[$id] = array(
					"name" 	=> $name,
					"seen"	=> time()
					);
			}elseif($type == "send" && $this->current_user && isset($data['data']['type'])){

				// make new models to stored data
				$this->ws_messages = new WsMessages();

				$msg = $data['data']['type'] == 1 ? htmlspecialchars($data['data']['msg']) : $data['data']['file_name'];
				$type = $data['data']['type'];
				$room = $data['data']['room'];
				$this->ws_messages->user_id = $this->current_user;
				$this->ws_messages->msg = $msg;
				$this->ws_messages->post_id = $room;
				$this->ws_messages->msg_type = $type;
				$this->ws_messages->post_type = 1;
				$this->ws_messages->save(false);
				$this->ws_messages->post->comment_count ++;
				$this->ws_messages->post->update();

				$userProfile = json_decode($this->userProfile());

				// for list chat box
				$list_chat_inbox = $this->updateListChatBox();

				foreach ($this->clients as $client) {
					$this->send($client, "single", [array(
														'name'=>$userProfile->name,
														'avatar'=> $userProfile->image,
														'msg'=> nl2br($msg),
														'msg_type' => $type,
														"created_at" => date("h:i A"),
														'post_id'=> $room,
														"user_current" => $userProfile->current,
														"update_list_chat" => $list_chat_inbox
														)
													]);
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
		$data_result=[];
		$message = $this->ws_messages->find()->where('post_id ='.$this->post_id)->orderBy(['created_at'=> SORT_ASC])->with('user','user.profile')->all();
		if($message) {
			foreach ($message as $key => $value) {
       			# code...
				$time = UtilitiesFunc::FormatTimeChat($value->created_at);

				if ($value->user->profile->photo == null){
					$image = '/img/icon/no_avatar.jpg';
				}else{
					$image = '/uploads/'.$value->user->id.'/'.$value->user->profile->photo;
				}
				$current = 0;
				if($value->user->id == $this->current_user){
					$current = 1;
				}

				$item = array(
					'name'=>$value->user->profile->first_name ." ".$value->user->profile->last_name,
					'avatar'=> $image,
					'msg'=> nl2br($value->msg),
					'msg_type' => $value->msg_type,
					'created_at'=> $time,
					'post_id'=> $value->post_id,
					'user_current'=> $current
					);

				array_push($data_result,$item);
			}
		}
		return $data_result;
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

	public function userProfile()
	{
		$user = User::find()->where('id = '.$this->current_user)->with('profile')->one();
		if ($user->profile->photo == null){
			$image = '/img/icon/no_avatar.jpg';
		}else{
			$image = '/uploads/'.$user->id.'/'.$user->profile->photo;
		}
		$current = 0;
		if($user->id == $this->current_user){
			$current = 1;
		}

		return $data = json_encode([
			'name' => $user->profile->first_name ." ".$user->profile->last_name,
			'image' => $image,
			'current' => $current
		]);
	}

	public function updateListChatBox()
	{


        $messages = new WsMessages();
		$messages = $messages->find()->select('post_id')->where('user_id = '.$this->current_user)
		        ->distinct()
		        ->with('post')
		        ->all();
        if($messages) {
            $data = [];
        	$user = new User();
            foreach ($messages as $key => $message) {

                $user_photo = $user->findOne($message->post->user_id)->profile->photo;
                if ($user_photo == null){
                    $image = 'img/icon/no_avatar.jpg';
                }else{
                    $image = 'uploads/'.$message->post->user_id.'/'.$user_photo;
                }

                $num_comment = UtilitiesFunc::ChangeFormatNumber($message->post->comment_count ? $message->post->comment_count + 1 : 1);
                $num_brilliant = UtilitiesFunc::ChangeFormatNumber($message->post->brilliant_count ? $message->post->brilliant_count : 0);
                $num_date = UtilitiesFunc::FormatDateTime($message->post->updated_at);

                $item = [
                    'id'=> $message->post->id,
                    'post_title'=> $message->post->title,
                    'post_content'=> $message->post->content,
                    'topic_id'=> $message->post->topic_id,
                    'title'=> $message->post->title,
                    'content'=> $message->post->content,
                    'num_comment' => $num_comment ? $num_comment: 0,
                    'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                    'avatar'=> $image,
                    'update_at'=> $num_date,
                    'real_update_at' => $message->post->updated_at
                    ];
                array_push($data, $item);
            }
            // return strtotime($data[0]['real_update_at']) - strtotime($data[1]['real_update_at']);die;
            usort($data, function($a, $b) {
                return strtotime($b['real_update_at']) - strtotime($a['real_update_at']);
            });
            $data = json_encode($data);
            return $data;
        } else {
            return false;
        }
	}
}
?>