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

	public function actionIndex()
	{
		$statusFile = Yii::getAlias('@frontend/modules/netwrk')."/bg-file/serverStatus.txt";
		$status = file_get_contents($statusFile);
		if($status == 0){
			/* This means, the WebSocket server is not started. So we, start it */

			$this->actionExecInbg("php ".Yii::getAlias('@console/controllers')."/ServerWSController.php");
			file_put_contents($statusFile, 1);
		}
		return $this->render('index');
	}

    public function actionChatName(){
    	$postId = $_POST['post'];

    	$post = POST::find()->where('id ='.$postId)->with('topic')->one();

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

		return $this->render($this->getIsMobile() ? 'mobile/index' : '' , ['post' =>$post] );
	}
}