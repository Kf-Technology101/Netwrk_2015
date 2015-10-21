<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render($this->getIsMobile() ? 'mobile/index' : 'index');
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
    	$cities = City::find()->orderBy(['user_count'=> SORT_DESC])->limit(10)->all();
    	
    	$data = [];

    	foreach ($cities as $key => $value) {
    		$netwrk = array(
    			'id'=> $value->id,
    			'name'=> $value->name,
    			'lat'=> $value->lat,
    			'lng'=>$value->lng,
    			'zip_code'=> $value->zip_code
    		);
    		array_push($data,$netwrk);
    	}

    	$hash = json_encode($data);
    	return $hash;
    }

    public function actionGetMakerMaxZoom()
    {
    	$cities = City::find()->orderBy(['user_count'=> SORT_DESC])->all();
    	
    	$data = [];

    	foreach ($cities as $key => $value) {
    		$netwrk = array(
    			'id'=> $value->id,
    			'name'=> $value->name,
    			'lat'=> $value->lat,
    			'lng'=>$value->lng,
    			'zip_code'=> $value->zip_code
    		);
    		array_push($data,$netwrk);
    	}

    	$hash = json_encode($data);
    	return $hash;
    }
}
