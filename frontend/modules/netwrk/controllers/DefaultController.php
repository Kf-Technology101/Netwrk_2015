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

    public function actionCheckExistPlaceZipcode()
    {
        $zipcode = $_POST['zipcode'];
        $place_name = $_POST['place_name'];
        $city = City::find()->where(['zip_code'=>$zipcode, 'office'=>$place_name])->one();

        if($city){
            $data = ['status'=> 1,'city'=>$city->id];
        }else{
            $cty = City::find()->where(['zip_code'=>$zipcode])->one();
            $data = ['status'=> 0, 'city_name'=>$cty->name];
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
            if(isset($value->topics[0])) {
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
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
        			'post'=> array(
                        'post_id'=>$post->id,
    		    		'name_post'=> $post->title,
        				'content' => $content,
        			)
        		);
        		array_push($data,$netwrk);
            } else {
                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'post'=> array(
                        'post_id'=>-1,
                        'name_post'=> '',
                        'content' => '',
                    )
                );

                array_push($data,$netwrk);
            }
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
            if(isset($value->topics[0])) {
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
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
        			'post'=> array(
                        'post_id'=>$post->id,
    		    		'name_post'=> $post->title,
        				'content' => $content,
    				)
        		);
        		array_push($data,$netwrk);
            } else {
                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'post'=> array(
                        'post_id'=>-1,
                        'name_post'=> '',
                        'content' => '',
                    )
                );

                array_push($data,$netwrk);
            }
    	}

    	$hash = json_encode($data);
    	return $hash;
    }

    public function actionPlaceSave(){
        $zipcode = $_POST['zip_code'];
        $city_name = $_POST['netwrk_name'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $office = $_POST['office'];
        $office_type = $_POST['office_type'];

        $netwrk = new City;
        $netwrk->name = $city_name;
        $netwrk->lat = $lat;
        $netwrk->lng = $lng;
        $netwrk->zip_code = $zipcode;
        $netwrk->office = $office;
        $netwrk->office_type = $office_type;
        $netwrk->save();
        return json_encode($netwrk->id);
    }
}
