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
                'city_office'=>$value['office'],
                'city_office_type'=>$value['office_type'],
                'user_id' => $value['user_id'],
                'status' => $value['status'],
                'log_id' => $value['log_id'],
                'lat' => $value['lat'],
                'lng' => $value['lng']
            );
            array_push($data,$item);

        }
        $returnData['data'] = $data;
        $hash = json_encode($returnData);
        return $hash;
    }

    public function actionDelete()
    {
        $returnData = array();
        $logId = $_GET['log_id'];
        $city_id = $_GET['city_id'];
        $type = $_GET['type'];
        $currentUser = Yii::$app->user->id;

        //delete log by id and if user is created that log entry
        $log = Log::find()->where(['id'=>$logId, 'city_id'=>$city_id, 'user_id'=>$currentUser])->one();
        if ($log->status == 1) {
            $log->status = 0;
            $log->save();
            $returnData['success'] = true;
            $returnData['message'] = 'Log deleted successfully';
        }
        return json_encode($returnData);
    }

}