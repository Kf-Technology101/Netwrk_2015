<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "favorite".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $user_id
 * @property string $type
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Favorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['status'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'user_id' => 'User ID',
            'city_id' => 'City ID',
            'type'  => 'Type',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at'

        ];
    }

    public static function isFavoritedByUser($objectType, $objectId, $userId)
    {
        $userId = $userId ? $userId : Yii::$app->user->id;

        $favorite = Favorite::find()->where('user_id = :userId and city_id = :cityId and type= :type')
            ->addParams(['userId'=>$userId, 'cityId'=>$objectId, 'type'=>$objectType])
            ->one();

        if ($favorite) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Get top netwrk have most user
    public function getFavoriteCommunitiesByUser($userId){
        $userId = $userId ? $userId : Yii::$app->user->id;

        $query = new Query();
        $data = $query->select('favorite.id as favorite_id, favorite.user_id, favorite.status, city.id as city_id, city.zip_code, city.name')
            ->from('favorite')
            ->join('INNER JOIN', 'city', 'city.id = favorite.city_id')
            ->where(['favorite.user_id' => $userId, 'favorite.status' => 1])
            ->all();

        return $data;
    }
}
