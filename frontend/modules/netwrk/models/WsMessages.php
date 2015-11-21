<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $id
 * @property string $name
 * @property double $lat
 * @property double $lng
 * @property integer $post_count
 * @property integer $user_count
 *
 * @property Topic[] $topics
 */
class WsMessages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ws_messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'post_id', 'post_type', 'msg_type'], 'number'],
            [['created_time', 'updated_time'], 'safe'],
            [['msg'], 'string', 'max' => 45],
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
}
