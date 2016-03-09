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
            'type'  => 'Type'
        ];
    }

}
