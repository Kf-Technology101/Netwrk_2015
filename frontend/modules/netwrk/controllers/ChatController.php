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
    public function actionIndex()
    {
    	return $this->render('chat');
    }

    public function actionChatPost(){
    	$postId = $_GET['post'];

    	$post = POST::find()->where('id ='.$postId)->with('topic')->one();

    	return $this->render($this->getIsMobile() ? 'mobile/index' : '' , ['post' =>$post] );
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
}