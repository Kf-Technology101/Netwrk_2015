<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "ws_messages".
 *
 * @property integer $id
 * @property integer $url
 * @property string $created_at
 * @property string $updated_at
 */
class ChatPrivate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_private';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'user_id_guest', 'post_id', 'created_at', 'updated_at'], 'required'],
            [['id', 'user_id', 'user_id_guest'], 'integer', 'max' => 255],
            [['post_id'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

   /**
     * @inheritdoc
     */
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_guest']);
    }

}