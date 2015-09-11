<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;

class TopicController extends BaseController{

  public function actionGetTopic($param){
    if ($param) {
      $topices = Topic::find()->where(['city_id'=>$param])->All();
      $data = array('status'=> 1,'data'=> $topices);
      return $data;
    }
  }

  public function actionGetTopicMobile($param) {
    $topices = Topic::find()->where(['city_id'=> $param])->All();
    return $this->render('mobile/index', ['topices' => $topices,'title' =>'Indianapolis']);
  }
  
}