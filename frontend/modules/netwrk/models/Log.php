<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $event
 * @property integer $status
 * @property string $message
 * @property string $created_at
 * @property integer $city_log_id
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['type'], 'required'],
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
            'type'  => 'Type',
            'created_at' => 'Created at',
            'city_log_id' => 'City Log ID',

        ];
    }

    //Get top netwrk have most user
    public function getRecentCommunitiesByUser($userId){
        $userId = $userId ? $userId : Yii::$app->user->id;

        $query = new Query();
        $data = $query->select('log.id as log_id, log.user_id, log.status, city.id as city_id, city.zip_code, city.name')
            ->from('log')
            ->join('INNER JOIN', 'city', 'city.id = log.city_id')
            ->where(['log.user_id' => $userId, 'log.status' => 1])
            ->all();

        return $data;
    }
}
