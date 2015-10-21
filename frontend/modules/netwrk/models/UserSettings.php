<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $distance
 * @property integer $age
 * @property string $gender
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'age'], 'integer'],
            [['distance'], 'number'],
            [['gender'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'distance' => 'Distance',
            'age' => 'Age',
            'gender' => 'Gender',
        ];
    }
}
