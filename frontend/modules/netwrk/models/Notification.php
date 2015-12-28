<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use frontend\modules\netwrk\models\WsMessages;
/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $topic_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'sender', 'receiver', 'message', 'status'], 'required'],
            [['message'], 'string'],
            [['post_id', 'sender', 'receiver'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'chat_show'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Room',
            'sender' => 'Sender',
            'receiver' => 'Receiver',
            'message' => 'Message',
            'status' => 'Read',
            'chat_show' => 'Chat Notify',
            'created_at' => 'Sent Date',
            'updated_at' => 'Updated At'  
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'value'      => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
        ];
    }
}
