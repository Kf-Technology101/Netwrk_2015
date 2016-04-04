<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Log;
use frontend\modules\netwrk\models\City;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;

class LogController extends BaseController
{

    public function actionCreate()
    {
        $type = $_POST['type'];
        $event = $_POST['event'];
        $user_id = $_POST['user_id'];
        $response = [];
        switch($type) {
            case 'city':
                $city_id = $_POST['city_id'];
                //todo: insert log for city
                $log = new Log();
                $log->user_id = $user_id;
                if ($type == 'city') {
                    //todo insert record in city log table
                    $log->city_id = $city_id;
                }
                $log->type = $type;
                $log->event = $event;
                $log->status = 1;
                $log->created_at = date('Y-m-d H:i:s');
                $log->save();
                $response['success'] = true;
                $response['message'] = 'Log saved';
                break;
            default:
                break;
        }

        return json_encode($response);
    }

    public function actionGetRecentCommunitiesByUser()
    {
        $returnData = array();
        $communities = Log::getRecentCommunitiesByUser(Yii::$app->user->id);

        $data = [];
        foreach ($communities as $key => $value) {

            $item = array(
                'city_id'=> $value['city_id'],
                'city_zipcode'=>$value['zip_code'],
                'city_name'=>$value['name'],
                'user_id' => $value['user_id'],
                'status' => $value['status']
            );
            array_push($data,$item);

        }
        $returnData['data'] = $data;
        $hash = json_encode($returnData);
        return $hash;
    }

}