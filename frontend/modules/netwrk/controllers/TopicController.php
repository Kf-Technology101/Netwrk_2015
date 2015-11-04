<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;

class TopicController extends BaseController
{ 
  private $currentUser = 1; 
  public function actionIndex()
  { 
    // $query = Topic::find()->where(['city_id'=>1])->orderBy(['post_count'=> SORT_DESC]);
    // $countQuery = clone $query;
    // $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>10, 'page'=>1]);
    // $models = $query->offset($pages->offset)
    //     ->limit($pages->pageSize)
    //     ->all();
    $posts = Post::find()->where('topic_id = 1')->orderBy(['created_at'=> SORT_DESC])->all();
    $num_post = count($posts);
    $data = [];

    foreach ($posts as $key => $post){
      if($key < 3){
        array_push($data,'#'.$post->title);
      }
    }
    $post_data = array(
      'data'=> $data,
      'num_post'=> $num_post - 3
    );
    return $this->render('index');
  }

  public function actionCreateTopic() {
    $city = $_GET['city'];
    $cty = City::findOne($city);
    if ($cty){
      $city_id = $cty->id;
      $name = $cty->name;
      $object = array(
        'city_name'=> $name,
        'status'=> 1
      );
    }else{
      $name = $_GET['name'];
      $zip_code = $_GET['zipcode'];
      $lat = $_GET['lat'];
      $lng = $_GET['lng'];
      $city_id = $city;
      $object = array(
        'status'=> 0,
        'city_name'=> $name,
        'zipcode'=> $zip_code,
        'lat'=> $lat,
        'lng'=> $lng,
        'city_id' => $city_id
      );
    }
    return $this->render('mobile/create',['city_id' =>$city_id,'data'=> (object)$object]);
  }

  public function actionNewTopic() {
    $city = $_POST['city'];
    $topic = $_POST['topic'];
    $post = $_POST['post'];
    $message = $_POST['message'];

    $current_date = date('Y-m-d H:i:s');

    $cty = City::findOne($city);
    $city_id = 0;

    if($cty){
      $city_id = $cty->id;
    }else{
      $zipcode = $_POST['zip_code'];
      $city_name = $_POST['netwrk_name'];
      $lat = $_POST['lat'];
      $lng = $_POST['lng'];

      $netwrk = new City;
      $netwrk->name = $city_name;
      $netwrk->lat = $lat;
      $netwrk->lng = $lng;
      $netwrk->zip_code = $zipcode;
      $netwrk->save();
      $city_id = $netwrk->id;
    }

    $Topic = new Topic;
    $Topic->user_id = $this->currentUser;
    $Topic->city_id = $city_id;
    $Topic->title = $topic;
    $Topic->created_at = $current_date;
    $Topic->updated_at = $current_date;
    $Topic->save();

    $Post = new Post;
    $Post->title = $post;
    $Post->content = $message;
    $Post->topic_id = $Topic->id;
    $Post->user_id = $this->currentUser;
    $Post->created_at = $current_date;
    $Post->updated_at = $current_date;
    $Post->save();

    $Topic->post_count = 1;
    $Topic->update();

    return $city_id;
  }

  public function actionGetTopicMobile()
  {
    $userId = 1;
    $city = $_GET['city'];
    
    $filter = $_GET['filter'];
    $pageSize = $_GET['size'];
    $page = $_GET['page'];
    $cty = City::findOne($city);
    if(!$cty){
      $zipcode = $_GET['zipcode'];
    }
    switch ($filter) {
      case 'post':
        $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['post_count'=> SORT_DESC]);
        break;
      case 'view':
        $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['view_count'=> SORT_DESC]);
        break;
      case 'topic':
        $topices = Topic::find()->where(['city_id'=>$city,'user_id'=>$userId])->orderBy(['created_at'=> SORT_DESC]);
        break;
    }

    $countQuery = clone $topices;
    $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
    $topices = $topices->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

    $data = [];
    foreach ($topices as $key => $value) {
      $num_view = UtilitiesFunc::ChangeFormatNumber($value->view_count);
      $num_post = UtilitiesFunc::ChangeFormatNumber($value->post_count - 3);
      $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
      $posts = Post::find()->where('topic_id ='.$value->id)->orderBy(['created_at'=> SORT_DESC])->all();
      $data_post = [];

      foreach ($posts as $key => $post){
        if($key < 3){
          array_push($data_post,'#'.$post->title);
        }
      }
      $post_data = array(
        'data_post'=> $data_post,
      );

      $topic = array(
        'id'=> $value->id,
        'city_id'=>$value->city_id,
        'title'=>$value->title,
        'post_count' => $value->post_count > 0 ? $value->post_count: 0,
        'post_count_format' => $num_post,
        'view_count'=> $num_view > 0 ? $num_view : 0,
        'img'=> Url::to('@web/img/icon/timehdpi.png'),
        'created_at'=>$num_date,
        'post'=> $post_data
      );
      array_push($data,$topic);
    }
    $temp = array ('data'=> $data ,'city' => $cty ? $cty->zip_code : $zipcode);
    $hash = json_encode($temp);

    return $hash;
  }

  public function actionTopicPage() {
    $city = $_GET['city'];

    $object = array();

    $cty = City::findOne($city);
    if ($cty){
      $city_id = $cty->id;
      $name = $cty->name;
      $object = array(
        'city_name'=> $name,
        'zipcode'=> $cty->zip_code,
        'status'=> 1
      );
    }else{
      
      $name = $_GET['name'];
      $zip_code = $_GET['zipcode'];
      $lat = $_GET['lat'];
      $lng = $_GET['lng'];
      $city_id = $city;
      $object = array(
        'status'=> 0,
        'city_name'=> $name,
        'zipcode'=> $zip_code,
        'lat'=> $lat,
        'lng'=> $lng,
        'city_id' => $city_id
      );
    }
    return $this->render('mobile/index', ['city_id' =>$city_id,'data'=> (object)$object]);
  }
  
  public function actionUpdateViewTopic(){
    $id = $_POST['topic'];

    $topic = Topic::findOne($id);
    $topic->view_count ++;
    $topic->update();
  }

  public function actionGetTopic(){
    $id = $_POST['topic'];

    $topic = Topic::findOne($id);

    return $topic->title;
  }
}