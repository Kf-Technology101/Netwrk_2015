<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\ChatPrivate;
use frontend\modules\netwrk\models\WsMessages;
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
		$post_id = $_GET['postID'];
		$user = User::find()->where('id = '. $user_id)->with('profile')->one();
		$currentUser = Yii::$app->user->id;
		if ($currentUser) {
			$data = ChatPrivate::find()->where('user_id = '.$currentUser. ' AND post_id = '. $post_id. ' AND user_id_guest = '.$user_id)->count();
			if ($data > 0) {
				$statusFile = Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt";
				$status = file_get_contents($statusFile);
				if($status == 0){
					/* This means, the WebSocket server is not started. So we, start it */
					$this->actionExecInbg("php yii server/run");
					file_put_contents($statusFile, 1);
				}

				return $this->render($this->getIsMobile() ? 'mobile/index' : '' , ['user' => $user, 'post_id' => $post_id ] );
			} else {
				return  $this->goHome();
			}
		} else {
			return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/chat-inbox/']);
		}


	}

	public function actionGetChatPrivateList()
	{
		$currentUser = Yii::$app->user->id;
		$chat_list = ChatPrivate::find()->where('user_id = '.$currentUser)->all();
		if ($chat_list) {
			$data = [];
			foreach ($chat_list as $key => $chat) {
				$num_date = UtilitiesFunc::FormatDateTime($chat->updated_at ? $chat->updated_at : $chat->created_at);
				$user_photo = User::findOne($chat->user_id_guest)->profile->photo;
				$content = WsMessages::find()->where('post_id = '.$chat->user_id_guest)->orderBy(['id'=> SORT_DESC])->one();
				if ($user_photo == null){
					$image = 'img/icon/no_avatar.jpg';
				}else{
					$image = 'uploads/'.$chat->user_id_guest.'/'.$user_photo;
				}
				$item = [
				'user_id_guest' => $chat->user->id,
				'user_id_guest_first_name' => $chat->user->profile->first_name,
				'user_id_guest_last_name' => $chat->user->profile->last_name,
				'updated_at'=> $num_date,
				'avatar' => $image,
				'content' => $content ? $content->msg : 'Matched!',
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

}