<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use yii\helpers\Url;
use yii\db\Query;

class TopicController extends BaseController
{

  public function actionGetTopic($city,$filter)
  {
    $userId = 1;
    switch ($filter) {
      case 'post':
        $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['post_count'=> SORT_DESC])->all();
        break;
      case 'view':
        $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['view_count'=> SORT_DESC])->all();
        break;
      case 'topic':
        $topices = Topic::find()->where(['city_id'=>$city,'user_id'=>$userId])->orderBy(['created_at'=> SORT_DESC])->all();
        break;
    }
    $data = [];
    foreach ($topices as $key => $value) {
      $num_view = $this->ChangeFormatNumber($value->view_count);
      // $num_view = $this->ChangeFormatNumber(23213122);
      $num_date = $this->FormatDateTime($value->created_at);
      $topic = array(
        'id'=> $value->id,
        // 'user_id'=> $value->user_id,
        'city_id'=>$value->city_id,
        'title'=>$value->title,
        'post_count' => $value->post_count,
        'view_count'=> $num_view,
        'img'=> Url::to('@web/img/icon/timehdpi.png'),
        'created_at'=>$num_date
      );
      array_push($data,$topic);
    }
    $hash = json_encode($data);

    return $hash;
  }

  public function actionTopicPage($city) {
    // $topices = Topic::find()->where(['city_id'=> $param])->All();
    return $this->render('mobile/index', ['city' =>$city]);
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
    // $date1 = strtotime('2015-1-31 15:30:00');
    // $date2 = strtotime('2015-1-31 15:30:00');
    // $time1 = date_create('2015-1-31 15:30:00');
    // $time2 = date_create('2015-1-31 15:30:00');

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
      }elseif($mweek < $mmonth && $mweek == 0 ){
        $count_time = "{$dweek} wks";
      }elseif($mweek > $mmonth && $mmonth == 0){
        $count_time = "{$dmonth} mo";
      }else{
        $count_time = "{$ddays} days";
      }
    }elseif($ddays > 100 && $ddays < 730){
      if($mweek < $mmonth && $mweek < $myear ){
        $count_time = "{$dweek} wks";
      }elseif($mweek > $mmonth && $mmonth < $myear){
        $count_time = "{$dmonth} mo";
      }elseif($mweek > $myear && $mmonth > $myear){
        $count_time = "{$dyear} yr";
      }
    }elseif ($ddays >= 730 && $ddays < 2000) {
      if($myear > $mmonth){
        $count_time = "{$dmonth} mo";
      }else{
        $count_time = "{$dyear} yr";
      }
    }else{
      $count_time = "{$dyear} yr";
    }
    return $count_time;
  }
}