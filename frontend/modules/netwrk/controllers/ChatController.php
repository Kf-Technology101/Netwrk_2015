<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;

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
		$post->update();
		$info = array(
			'post_name'=> $post->title,
			'topic_name'=> $post->topic->title,
			);

		$hash = json_encode($info);
		return $hash;
	}

	public function actionChatPost(){
		$postId = $_GET['post'];

		$post = POST::find()->where('id ='.$postId)->with('topic')->one();
		$post->update();
		$statusFile = Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt";
		$status = file_get_contents($statusFile);
		if($status == 0){
			/* This means, the WebSocket server is not started. So we, start it */
			$this->actionExecInbg("php yii server/run");
			file_put_contents($statusFile, 1);
		}
		return $this->render($this->getIsMobile() ? 'mobile/index' : '' , ['post' =>$post] );
	}

	public function actionUpload()
	{
		if(isset($_FILES['file'])){
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
				"application/excel" => 'xls',
				"application/vnd.ms-excel" => 'xls',
				"application/x-excel" => 'xls',
				"application/x-msexcel" => 'xls',
				"application/mspowerpoint" => 'ppt',
				"application/powerpoint" => 'ppt',
				"application/vnd.ms-powerpoint" => 'ppt',
				"application/x-mspowerpoint" => 'ppt',
				"application/pdf" => 'pdf',
				"audio/mpeg3" => 'mp3',
				"video/mpeg" => "mp3",
				"video/avi" => "avi"
				);

			if (isset($supported_img_types[$mime_type])) {
				$extension = $supported_img_types[$mime_type];
				$type = 1;
				$location = Yii::getAlias('@frontend/web/img/uploads/');
			} else if(isset($supported_file_types[$mime_type])) {
				$extension = $supported_file_types[$mime_type];
				$type = 2;
				$location = Yii::getAlias('@frontend/web/files/uploads/');
			} else {
				$extension = false;
			}

			if($extension !== false && $_FILES['file']['size'] <= 2097152) {
				$info = pathinfo($_FILES['file']['name']);
				$fileName = uniqid(basename($_FILES['file']['name'],'.'.$info['extension']).date("ymd").'_') . "." . $extension;

				$location = $location. $fileName;
				move_uploaded_file($_FILES['file']['tmp_name'], $location);
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