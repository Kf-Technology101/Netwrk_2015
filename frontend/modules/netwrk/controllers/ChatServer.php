<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use Ratchet\MessageComponentInterface;
use frontend\components\BaseController;
use Ratchet\ConnectionInterface;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\WsMessages;
use frontend\modules\netwrk\models\ChatPrivate;
use frontend\modules\netwrk\models\Notification;
use frontend\modules\netwrk\models\UserMeet;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use Yii;

class ChatServer extends BaseController implements MessageComponentInterface {

	protected $clients;
	protected $ws_messages;
	protected $current_user;
	protected $post_id = 0;
	protected $chat_type;
	protected $notify;

	private $users = array();
	private $onl = [];

	public function __construct()
	{
		$this->ws_messages = new WsMessages();
		$this->clients 	= new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn)
	{
		$user_id = $conn->WebSocket->request->getQuery()->get('user_id');
		$this->current_user = $user_id;
		$this->clients->attach($conn);
		$this->checkOnliners();
		// if($user_id != ""){
		// 	$this->send($conn, "notify", $this->notify());
		// }
		echo "User with id ".$user_id . " is connected on ({$conn->resourceId})\n";
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
			}elseif($type == "send" && isset($data['data']['type']) && isset($data['data']['chat_type']) && isset($data['data']['user_id'])){
				// make new models to stored data
				$this->chat_type = $data['data']['chat_type'];
				$this->ws_messages = new WsMessages();
				$msg = $data['data']['type'] == 1 ? htmlspecialchars($data['data']['msg']) : $data['data']['file_name'];
				$msg_type = $data['data']['type'];
				$room = $data['data']['room'];
				// $this->post_id = $data['data']['room'];
				$user = $data ['data']['user_id'];
				// $this->current_user = $user;
				$this->ws_messages->user_id = $user;
				$this->ws_messages->msg = $msg;
				$this->ws_messages->post_id = $room;
				$this->ws_messages->msg_type = $msg_type;
				$this->ws_messages->post_type = $this->chat_type;
				$this->ws_messages->save(false);

				$u = ChatPrivate::find()->where(['user_id'=>$user, 'post_id'=>$room])->one();
				if($u){
					$this->notify = new Notification();
					$this->notify->post_id = $room;
					$this->notify->sender = $user;
					$this->notify->receiver = $u->user_id_guest;
					$this->notify->message = $this->ws_messages->id;
					$this->notify->status = 0;
					$this->notify->chat_show = 0;
					$this->notify->created_at = date('Y-m-d H:i:s');
					$this->notify->save();
				}
				if ($this->chat_type == 1) {
					$this->ws_messages->post->comment_count ++;
					$this->ws_messages->post->update();
					$list_chat_inbox = $this->updateListChatBox($user);
				} else {
					$chat_private = ChatPrivate::find()->where('post_id = '.$this->ws_messages->post_id . ' AND user_id = ' .$user)->one();

					if ($chat_private) {
						$chat_private->save(false);
					}
					$list_chat_inbox = $this->updateChatPrivateList($user);
				}
				$userProfile = json_decode($this->userProfile($user));
				// for list chat box

				foreach ($this->clients as $client) {
					$this->send($client, "single", [array(
														'id'=> $user,
														'name'=>$userProfile->name,
														'avatar'=> $userProfile->image,
														'msg'=> nl2br($msg),
														'msg_type' => $msg_type,
														"created_at" => date("h:i A"),
														'post_id'=> $room,
														// "user_current" => $userProfile->current,
														"update_list_chat" => $list_chat_inbox,
														"chat_type" => $this->chat_type
														)
													]);
				}

			}elseif($type == "fetch"){
				$this->post_id = isset($data['data']['post_id']) ? $data['data']['post_id'] : false;
				$this->chat_type = isset($data['data']['chat_type']) ? $data['data']['chat_type']: false;
				$this->send($from, "fetch", $this->fetchMessages());
			}elseif($type == "notify"){
				$sender = $data['data']['sender'];
				$receiver = $data['data']['receiver'];
				$room = $data['data']['room'];
				$message = $data['data']['message'];
				if($receiver != -1 && $room == -1){
					$num_date_first_met = date('M d');
					$msg = 'Matched on <span class="matched-date">'.$num_date_first_met.'</span>';
					$checkMet = UserMeet::find()->where(['user_id_1'=>$receiver, 'user_id_2'=>$sender])->one();
					if(count($checkMet) > 0){
						$_room = ChatPrivate::find()->where(['user_id'=>$sender, 'user_id_guest'=>$receiver])->one();
						$ws_msg_id = $this->insertWsMessage($sender, $msg, $_room->post_id, 1, 0, 0);
						if($ws_msg_id != false){
							$this->insertNotification($_room->post_id, $sender, $receiver, $ws_msg_id, 0, 0, date('Y-m-d H:i:s'));
							$this->insertNotification($_room->post_id, $receiver, $sender, $ws_msg_id, 0, 0, date('Y-m-d H:i:s'));
						}
					}
				}
				foreach ($this->clients as $client) {
					$concept = $this->notify($sender, $room, $message, $receiver);
					if($concept != 0)
						$this->send($client, "notify", $concept);
				}
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
		$message = $this->ws_messages->find()->where('post_id ='.$this->post_id. ' AND post_type = '.$this->chat_type)->orderBy(['created_at'=> SORT_ASC])->with('user','user.profile')->all();
		if($message) {
			foreach ($message as $key => $value) {
       			# code...
       			if($value->first_msg == 0){
       				if($value->user->id == $this->current_user){
       					$pchat = ChatPrivate::find()->where(['user_id'=>$value->user->id, 'post_id'=>$this->post_id])->one();
       					$profile = Profile::find()->where(['user_id'=>$pchat->user_id_guest])->one();
       				} else {
       					$profile = Profile::find()->where(['user_id'=>$value->user->id])->one();
       				}

       				$current_date = date('Y-m-d H:i:s');
		            $time1 = date_create($profile->dob);
		            $time2 = date_create($current_date);
		            $year_old = $time1->diff($time2)->y;

       				$smg = nl2br($profile->first_name . " " . $profile->last_name . ", " . $year_old . "\n" . $value->msg);

       			} else {
       				$smg = nl2br($value->msg);
       			}

				$time = UtilitiesFunc::FormatTimeChat($value->created_at);
				if ($value->user->profile->photo == null){
					$image = '/img/icon/no_avatar.jpg';
				}else{
					$image = '/uploads/'.$value->user->id.'/'.$value->user->profile->photo;
				}
				$item = array(
					'id'=>$value->user->id,
					'name'=>$value->user->profile->first_name ." ".$value->user->profile->last_name,
					'avatar'=> $image,
					'msg'=> $smg,
					'msg_type' => $value->msg_type,
					'created_at'=> $time,
					'post_id'=> $value->post_id,
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
		$data = $this->current_user;
		array_push($this->onl, $data);
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

	public function userProfile($user_id)
	{
		$user = User::find()->where('id = '.$user_id)->with('profile')->one();

		if ($user->profile->photo == null){
			$image = '/img/icon/no_avatar.jpg';
		}else{
			$image = '/uploads/'.$user->id.'/'.$user->profile->photo;
		}
		// $current = 0;
		// if($user->id == $this->current_user){
		// 	$current = 1;
		// }
		return $data = json_encode([
			'name' => $user->profile->first_name ." ".$user->profile->last_name,
			'image' => $image,
			// 'current' => $current
		]);
	}

	public function updateListChatBox($user_id)
	{
        $messages = new WsMessages();
		$messages = $messages->find()->select('post_id')->where('user_id = '.$user_id. ' AND post_type = 1')
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
                $num_date = UtilitiesFunc::FormatDateTime($message->post->created_at);

                $item = [
                    'id'=> $message->post->id,
                    'post_title'=> $message->post->title,
                    'post_content'=> $message->post->content,
                    'topic_id'=> $message->post->topic_id,
                    'topic_name'=> $message->post->topic->title,
                    'city_id' => $message->post->topic->city_id,
                    'city_name' => $message->post->topic->city->name,
                    'title'=> $message->post->title,
                    'content'=> $message->post->content,
                    'num_comment' => $num_comment ? $num_comment: 0,
                    'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                    'avatar'=> $image,
                    'update_at'=> $num_date,
                    'real_update_at' => $message->post->updated_at ? $message->post->updated_at : $message->post->created_at
                    ];
                array_push($data, $item);
            }
            usort($data, function($a, $b) {
                return strtotime($b['real_update_at']) - strtotime($a['real_update_at']);
            });
            $data = json_encode($data);
            return $data;
        } else {
            return false;
        }
	}

	public function updateChatPrivateList($user_id)
	{
		$chat_list = ChatPrivate::find()->where('user_id = '.$user_id )->all();
		if ($chat_list) {
			$data = [];
			foreach ($chat_list as $key => $chat) {
				$num_date = UtilitiesFunc::FormatDateTime($chat->updated_at ? $chat->updated_at : $chat->created_at);
				$user_photo = User::findOne($chat->user_id_guest)->profile->photo;
				$content = WsMessages::find()->where('post_id = '.$chat->post_id. ' AND post_type = 0')->orderBy(['id'=> SORT_DESC])->one();
				if ($user_photo == null){
                    $image = 'img/icon/no_avatar.jpg';
                }else{
                    $image = 'uploads/'.$chat->user_id_guest.'/'.$user_photo;
                }
                $num_date_first_met = date('M d', strtotime($chat->created_at));
                $content = $content ? ($content->msg_type == 1 ? $content->msg : 'Attached file') : 'Matched on <span class="matched-date">'.$num_date_first_met.'</span>';
				$item = [
					'user_id_guest' => $chat->user->id,
					'user_id_guest_first_name' => $chat->user->profile->first_name,
					'user_id_guest_last_name' => $chat->user->profile->last_name,
					'updated_at'=> $num_date,
					'avatar' => $image,
					'content' => $content,
					'post_id' => $chat->post_id,
					'real_updated_at' => $chat->updated_at ? $chat->updated_at : $chat->created_at
				];

				array_push($data, $item);
			}

			usort($data, function($a, $b) {
                return strtotime($b['real_updated_at']) - strtotime($a['real_updated_at']);
            });
			$data = json_encode($data);
			return $data;
		} else {
			return false;
		}
	}

	public function notify($sender, $room, $message, $_receiver){
		$notify = [];
		if($_receiver == -1 && $room != -1){
			$receiver = ChatPrivate::find()->where(['user_id'=>$sender, 'post_id'=>$room])->one();
			$chat_count = Notification::find()->select('sender')->where(['receiver'=>$receiver->user_id_guest, 'status'=>0])->distinct()->count();
			$msg_count = Notification::find()->where(['sender'=>$sender, 'receiver'=>$receiver->user_id_guest, 'status'=>0])->count();
			$notify = array('sender'=>$sender, 'receiver'=>$receiver->user_id_guest, 'message'=>$message, 'chat_count'=>$chat_count, 'msg_count'=>$msg_count, 'room'=>$room, 'ismeet'=>0);
		} else {
			$num_date_first_met = date('M d');

			$msg = 'Matched on <span class="matched-date">'.$num_date_first_met.'</span>';
			$checkMet = UserMeet::find()->where(['user_id_1'=>$_receiver, 'user_id_2'=>$sender])->one();
			if(count($checkMet) > 0){
				$_room = ChatPrivate::find()->where(['user_id'=>$sender, 'user_id_guest'=>$_receiver])->one();

				$chat_count = Notification::find()->select('sender')->where(['receiver'=>$_receiver, 'status'=>0])->distinct()->count();
				$msg_count = Notification::find()->where(['sender'=>$sender, 'receiver'=>$_receiver, 'status'=>0])->count();

				$notify = array('sender'=>$sender, 'receiver'=>$_receiver, 'message'=>$msg, 'chat_count'=>$chat_count, 'msg_count'=>$msg_count, 'room'=>$_room->post_id, 'ismeet'=>1);
			}
		}
		if(count($notify) > 0)
			return $notify;
		else
			return 0;
	}

	public function insertWsMessage($user_id, $msg, $post_id, $msg_type, $post_type, $first_msg){
		$this->ws_messages = new WsMessages();
		$this->ws_messages->user_id = $user_id;
		$this->ws_messages->msg = $msg;
		$this->ws_messages->post_id = $post_id;
		$this->ws_messages->msg_type = $msg_type;
		$this->ws_messages->post_type = $post_type;
		$this->ws_messages->first_msg = $first_msg;
		if($this->ws_messages->save(false))
			return $this->ws_messages->id;
		else
			return false;
	}

	public function insertNotification($post_id, $sender, $receiver, $msg, $status, $chat_show, $date){
		$this->notify = new Notification();
		$this->notify->post_id = $post_id;
		$this->notify->sender = $sender;
		$this->notify->receiver = $receiver;
		$this->notify->message = $msg;
		$this->notify->status = $status;
		$this->notify->chat_show = $chat_show;
		$this->notify->created_at = $date;
		return $this->notify->save(false);
	}
}
?>
