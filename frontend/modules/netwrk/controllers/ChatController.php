<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\ChatPrivate;
use frontend\modules\netwrk\controllers\Resize;
use yii\helpers\Url;

class ChatController extends BaseController
{
	public function actionExecInbg($cmd)
	{
		if (substr(php_uname(), 0, 7) == "Windows"){
			pclose(popen("start /B ". $cmd, "r"));
		} else {
			exec($cmd . " > /dev/null &");
		}
	}

	public function actionChatName(){
		$postId = $_POST['post'];

		$post = POST::find()->where('id ='.$postId)->with('topic')->one();
		// $post->update();
		$info = array(
			'post_name'=> $post->title,
			'topic_name'=> $post->topic->title,
			);

		$hash = json_encode($info);
		return $hash;
	}

	public function actionChatPost(){
		$postId = $_GET['post'];
		$chatType = isset($_GET['chat_type']) ? $_GET['chat_type'] : 1;
		$userCurrent = Yii::$app->user->id;

		$post = POST::find()->where('id ='.$postId)->with('topic')->one();
		if ($chatType == 0) {
			$user_id = ChatPrivate::find()->where('post_id ='.$postId.' AND user_id='. $userCurrent)->with('user')->one();
		} else {
			$user_id = '';
		}
		// $post->update();
		$statusFile = Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt";
		$status = file_get_contents($statusFile);
		if($status == 0){
			/* This means, the WebSocket server is not started. So we, start it */
			$this->actionExecInbg("php yii server/run");
			file_put_contents($statusFile, 1);
		}
		$url = Url::base(true).'/netwrk/chat/chat-post?post='.$postId.'&chat_type='.$chatType;
		return $this->render($this->getIsMobile() ? 'mobile/index' : '' , ['user_id' => $user_id, 'post' =>$post,'url'=> $url,'current_user'=>$userCurrent] );
	}

	public function actionUpload()
	{
		if(isset($_FILES['file']) && $_FILES['file']['error'] == 0 && isset($_POST['post'])){
			$file = file_get_contents($_FILES['file']['tmp_name']);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);

			$supported_img_types = array(
				"image/png" => "png",
				"image/jpeg" => "jpg",
				"image/pjpeg" => "jpg",
				"image/gif" => "gif"
				);

			$supported_file_types = array(
				"text/plain" => "txt",
				"application/msword" => 'doc',
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document" => 'docx',
				"application/excel" => 'xls',
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => 'xlsx',
				"application/vnd.ms-excel" => 'xls',
				"application/x-excel" => 'xls',
				"application/x-msexcel" => 'xls',
				"application/mspowerpoint" => 'ppt',
				"application/powerpoint" => 'ppt',
				"application/vnd.ms-powerpoint" => 'ppt',
				"application/x-mspowerpoint" => 'ppt',
				"application/vnd.openxmlformats-officedocument.presentationml.presentation" => 'pptx',
				"application/pdf" => 'pdf',
				"audio/mpeg3" => 'mp3',
				"video/mpeg" => "mp3",
				"video/avi" => "avi",
				"application/x-shockwave-flash" => 'swf',
				"audio/wav, audio/x-wav" => 'wav',
				"application/xml" => 'xml',
				"image/x-icon" => 'ico'
				);
			if (isset($supported_img_types[$mime_type])) {
				$extension = $supported_img_types[$mime_type];
				$type = 2;
				$location = Yii::getAlias('@frontend/web/img/uploads/').$_POST['post'].'/';

				if (!is_dir($location)) {
		            mkdir( $location, 0777, true);
		        }

			} else if(isset($supported_file_types[$mime_type])) {
				$extension = $supported_file_types[$mime_type];
				$type = 3;
				$location = Yii::getAlias('@frontend/web/files/uploads/').$_POST['post'].'/';

				if (!is_dir($location)) {
		            mkdir( $location, 0777, true);
		        }

			} else {
				$extension = false;
			}

			if($extension !== false && $_FILES['file']['size'] <= 50331648) {
				$info = pathinfo($_FILES['file']['name']);
				$fileName = uniqid(basename($_FILES['file']['name'],'.'.$info['extension']).date("ymd").'_') . "." . $extension;

				$target_upload = $location. $fileName;
				move_uploaded_file($_FILES['file']['tmp_name'], $target_upload);

				if ($type == 2) {
					$images_size = getimagesize($target_upload);
					$old_width = $images_size[0];
					$old_height = $images_size[1];
					//$final_width  = round($old_width/10);
					//$final_height = round($old_height/10);
					// *** 1) Initialise / load image
					$resizeObj = new Resize($target_upload);

					// Get optimal width and height so image not blur
					$final_size = $resizeObj->getSizeByAuto(100,100);
					$final_width  = $final_size['optimalWidth'];
					$final_height = $final_size['optimalHeight'];

					// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
					$resizeObj->resizeImage($final_width, $final_height, 'crop');

					// *** 3) Save image
					if (!is_dir($location.'/thumbnails/')) {
						mkdir( $location.'/thumbnails/', 0777, true);
			        }
					$resizeObj->saveImage($location.'/thumbnails/'.'thumbnail_'.$fileName, 100);
				}

				 $data = [
					'file_name' => $fileName,
					'type' => $type
				];
				$data = json_encode($data);
				return $data;
			} else {
				return false;
			}
		}
	}
}