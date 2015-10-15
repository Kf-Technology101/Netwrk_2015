<?php

namespace frontend\modules\netwrk\controllers;

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
    echo "pre"; print_r($post_data); die;
    return $this->render('index');
  }

  public function actionCreateTopic($city) {
    $cty = City::findOne($city);
    return $this->render('mobile/create',['city' =>$cty]);
  }

  public function actionNewTopic() {
    $city = $_POST['city'];
    $topic = $_POST['topic'];
    $post = $_POST['post'];
    $message = $_POST['message'];
    $current_date = date('Y-m-d H:i:s');

    $Topic = new Topic;
    $Topic->user_id = $this->currentUser;
    $Topic->city_id = $city;
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
  }

  public function actionGetTopicMobile()
  {
    $userId = 1;
    $city = $_GET['city'];
    $filter = $_GET['filter'];
    $pageSize = $_GET['size'];
    $page = $_GET['page'];
    $cty = City::findOne($city);
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
      $num_view = $this->ChangeFormatNumber($value->view_count);
      $num_post = $this->ChangeFormatNumber($value->post_count - 3);
      $num_date = $this->FormatDateTime($value->updated_at);
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
    $temp = array ('data'=> $data ,'city' => $cty->name);
    $hash = json_encode($temp);

    return $hash;
  }

  public function actionTopicPage($city) {
    $cty = City::findOne($city);
    return $this->render('mobile/index', ['city' =>$cty]);
  }
  
  public function changeFormatNumber($num)
  {
    $fnum = $num;
    if($fnum >=1000 && $fnum < 99999 ){
      $fnum = intval($fnum/1000);
      $fnum = "{$fnum}K";
    }elseif($fnum >= 100000 && $fnum < 999999){
      $fnum = round(floatval($fnum/1000000),1);
      $fnum = "{$fnum}M";
    }elseif($fnum >= 1000000){
      $fnum = round(floatval($fnum/10000000),1);
      $fnum = "{$fnum}G";
    }
    return $fnum;
  }

  public function getDateTime($date)
  {
    $current_date = date('Y-m-d H:i:s');

    // for test
    // $date1 = strtotime('2014-5-30 15:30:00');
    // $date2 = strtotime('2014-5-30 15:30:29');
    // $time1 = date_create('2014-5-30 15:30:00');
    // $time2 = date_create('2014-5-30 15:30:29');

    $date1 = strtotime($date);
    $date2 = strtotime($current_date);
    $time1 = date_create($date);
    $time2 = date_create($current_date);

    $diff = $date2 - $date1;
    $diff_days = floor($diff/(60*60*24));


    $dsecond = $time1->diff($time2)->s;
    $dminutes = $time1->diff($time2)->i;
    $dhours = $time1->diff($time2)->h;


    $time = array(
      'total_days' => $diff_days,
      'hours' => $dhours,
      'minutes' => $dminutes,
      'second' => $dsecond
    );

    return $time;
  }

  public function FormatDateTime($date){
    $diff = $this->GetDateTime($date);

    $ddays = $diff['total_days'];
    $mweek = $diff['total_days'] % 7;
    $dweek = intval($diff['total_days'] / 7);

    $mmonth = $diff['total_days'] % 30;
    $dmonth = intval($diff['total_days'] / 30);

    $myear = $diff['total_days'] % 365;
    $dyear = intval($diff['total_days'] / 365);

    if($ddays == 0){
      if ($diff['hours'] == 0){
        if($diff['minutes'] == 0){
          if($diff['second'] == 0 || $diff['second'] < 60 )
            $count_time = "Just now";
        }else{
          $count_time = "{$diff['minutes']} min";
        }
      }elseif ($diff['hours'] == 1){
        $count_time = "{$diff['hours']} hr";
      }else{
        $count_time = "{$diff['hours']} hrs";
      }
    }elseif($ddays <= 99){
      $marray = array($mweek,$mmonth);
      $darray = array($dweek,$dmonth);
      if($ddays == 1){
        $count_time = "{$ddays} day";
      }elseif($mweek < $mmonth && $mweek == 0 && $dweek == 1 ){
        $count_time = "{$dweek} wk";
      }elseif($mweek < $mmonth && $mweek == 0 && $dweek != 1){
        $count_time = "{$dweek} wks";
      }elseif($mweek > $mmonth && $mmonth == 0 && $dmonth == 1){
        $count_time = "{$dmonth} mo";
      }elseif($mweek > $mmonth && $mmonth == 0 && $dmonth != 1){
        $count_time = "{$dmonth} mos";
      }else{
        $count_time = "{$ddays} days";
      }
    }elseif($ddays == 365){
      $count_time = "{$dyear} yr";
    }elseif($ddays > 100 && $ddays < 730){
      if($mweek < $mmonth && $mweek < $myear ){
        $count_time = "{$dweek} wks";
      }elseif($mweek > $mmonth && $mmonth < $myear){
        $count_time = "{$dmonth} mos";
      }elseif($mweek > $myear && $mmonth > $myear){
        $count_time = "{$dyear} yrs";
      }
    }elseif ($ddays >= 730 && $ddays < 2000) {
      if($myear > $mmonth){
        $count_time = "{$dmonth} mos";
      }else{
        $count_time = "{$dyear} yrs";
      }
    }else{
      $count_time = "{$dyear} yrs";
    }
    return $count_time;
  }
}