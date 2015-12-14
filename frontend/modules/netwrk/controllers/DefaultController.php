<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use yii\web\Session;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\components\UtilitiesFunc;

class DefaultController extends BaseController
{

    public function actionIndex()
    {   
        return $this->render($this->getIsMobile() ? 'mobile/index' : 'index');
    }

    public function actionGetUserPosition()
    {   

    	$user = User::find()->where('id ='.Yii::$app->user->id)->with('profile')->one();
		$data =[
    		'lat'=> $user->profile->lat,
    		'lng'=> $user->profile->lng,
    	];

    	$hash = json_encode($data);
    	return $hash;
    }

    public function actionCheckExistZipcode()
    {
    	$zipcode = $_POST['zipcode'];
    	$city = City::find()->where(['zip_code'=>$zipcode])->one();

    	if($city){
    		$data = ['status'=> 1,'city'=>$city->id];
    	}else{
    		$data = ['status'=> 0];
    	}
    	$hash = json_encode($data);
    	return $hash;
    }

    public function actionGetMakerDefaultZoom()
    {
        $maxlength = Yii::$app->params['MaxlengthContent'];
    	$cities = City::find()->with('topics.posts')->orderBy(['user_count'=> SORT_DESC,'post_count'=> SORT_DESC])->limit(10)->all();

    	$data = [];


    	foreach ($cities as $key => $value) {
    		$post = $value->topics[0]->posts[0];
	    	$content = $post->content;

	    	if(strlen($content) > $maxlength ){
	    		$content = substr($post->content,0,$maxlength) ;
	    		$content = $content."...";
	    	}

    		$netwrk = array(
    			'id'=> $value->id,
    			'name'=> $value->name,
    			'lat'=> $value->lat,
    			'lng'=>$value->lng,
    			'zip_code'=> $value->zip_code,
    			'post'=> array(
		    		'name_post'=> $post->title,
    				'content' => $content,
    			)
    		);

    		array_push($data,$netwrk);
    	}

    	$hash = json_encode($data);
    	return $hash;
    }

    public function actionGetMakerMaxZoom()
    {
        $maxlength = Yii::$app->params['MaxlengthContent'];
    	$cities = City::find()->with('topics.posts')->orderBy(['post_count'=> SORT_DESC])->all();

    	$data = [];

    	foreach ($cities as $key => $value) {
    		$post = $value->topics[0]->posts[0];
	    	$content = $post->content;

	    	if(strlen($content) > $maxlength ){
	    		$content = substr($post->content,0,$maxlength ) ;
	    		$content = $content."...";
	    	}

    		$netwrk = array(
    			'id'=> $value->id,
    			'name'=> $value->name,
    			'lat'=> $value->lat,
    			'lng'=>$value->lng,
    			'zip_code'=> $value->zip_code,
    			'post'=> array(
		    		'name_post'=> $post->title,
    				'content' => $content,
				)
    		);
    		array_push($data,$netwrk);
    	}

    	$hash = json_encode($data);
    	return $hash;
    }
}
