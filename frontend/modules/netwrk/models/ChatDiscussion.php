<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\helpers\Url;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;
use yii\helpers\Inflector;
use ReflectionClass;

/**
 * This is the model class for table "chat_discussion".
 * @property integer $id
 * @property integer $post_id
 * @property integer $user_id
 * @property integer $notification_count
 * @property string $created_at
 * @property string $updated_at
 */
class ChatDiscussion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_discussion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id'], 'required'],
            [['user_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'notification_count'  => 'Notification Count',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at'

        ];
    }
}
