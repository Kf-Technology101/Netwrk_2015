<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property string $feedback
 * @property integer $point
 * @property integer $user_id
 * @property integer $ws_message_id
 * @property string $type
 * @property string $created_at
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feedback'], 'required'],
            [['point'], 'required'],
            [['user_id'], 'required'],
            [['type'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'feedback' => 'Feedback',
            'point' => 'Point',
            'user_id' => 'User ID',
            'ws_message_id' => 'WS Message ID',
            'type'  => 'Type',
            'created_at' => 'Created at'
        ];
    }
}
