<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "user_meet".
 *
 * @property integer $id
 * @property integer $user_id_1
 * @property integer $user_id_2
 */
class UserMeet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_meet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id_1', 'user_id_2'], 'required'],
            [['user_id_1', 'user_id_2'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id_1' => 'User Id 1',
            'user_id_2' => 'User Id 2',
        ];
    }
}
