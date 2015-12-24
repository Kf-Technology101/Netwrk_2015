<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use yii\web\Session;
use yii\db\Query;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\components\UtilitiesFunc;

class SearchController extends BaseController
{

    public function actionGlobalSearch(){
        $_search = $_POST['text'];

        if (Yii::$app->user->isGuest) {

        }else{
            $current_user = Yii::$app->user->identity;
            $cur_lat = $current_user->profile->lat;
            $cur_lng = $current_user->profile->lng;
        }

        $local =[];
        $netwrk_local = [];
        $radius_local = 50;
        $netwrk_global = [];

        $city = City::find()->all();

        foreach ($city as $key => $value) {
            $distance = UtilitiesFunc::CalculatorDistance($cur_lat,$cur_lng,$value->lat,$value->lng);
            if($distance <= $radius_local){
                $netwrk = [
                    'id'=>$value->id,
                    'name'=> $value->name,
                    'brilliant'=> $value->brilliant_count,
                ];
                array_push($netwrk_local, $netwrk);
            }
        }
        $data =[
            'data'=> $netwrk_local
        ];

        $hash = json_encode($data);
        return $hash;
    }
}