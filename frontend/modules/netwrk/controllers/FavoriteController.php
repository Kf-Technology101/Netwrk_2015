<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Favorite;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;

class FavoriteController extends BaseController
{

    public function actionFavorite()
    {
        $returnData = array();
        //Get params
        $object_type = isset($_GET['object_type']) ? $_GET['object_type'] : '';
        $currentUser = Yii::$app->user->id;

        //if object_type is city then find does currentUser already favorited city previously.
        if ($object_type == 'city') {
            $object_id = isset($_GET['object_id']) ? $_GET['object_id'] : '';
            $favorite = Favorite::find()->where('user_id = '.$currentUser.' AND city_id = '.$object_id)->one();
        }

        //If user already favorite/Unfavorite the object then UPDATE existing record else INSERT new record.
        if ($favorite) {
            $favorite->updated_at = date('Y-m-d H:i:s');
            if ($favorite->status == 1) {
                $favorite->status = 0;
                $favorite->save();
                $returnData['status'] = 'Favorite';
            } else {
                $favorite->status = 1;
                $favorite->save();
                $returnData['status'] = 'Favorited';
            }
        } else {
            $favorite = new Favorite;
            $favorite->user_id = $currentUser;
            if ($object_type == 'city') {
                $favorite->city_id = $object_id;
            }
            $favorite->type = $object_type;
            $favorite->status = 1;
            $favorite->created_at = date('Y-m-d H:i:s');
            $favorite->save();
            $returnData['status'] = 'Favorited';
        }

        if ($favorite) {
            $data = [
                'id' => $favorite->id,
                'user_id' => $favorite->user_id,
                'city_id' => $favorite->city_id,
                'type' => $favorite->type,
                'status' => $favorite->status,
                'created_at' => $favorite->created_at,
                'updated_at' => $favorite->updated_at
            ];
        }

        $returnData['success'] = 'true';
        $returnData['data'] = $data;
        $hash = json_encode($returnData);

        return $hash;
    }

    public function actionGetFavoriteCommunitiesByUser()
    {
        $returnData = array();
        $communities = Favorite::getFavoriteCommunitiesByUser(Yii::$app->user->id);

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