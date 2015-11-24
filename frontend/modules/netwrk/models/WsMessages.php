<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use frontend\modules\netwrk\models\Post;
/**
 * This is the model class for table "ws_messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $msg
 * @property integer $post_id
 * @property string $msg_type
 * @property integer $post_type
 * @property string $created_at
 * @property string $updated_at
 */
class WsMessages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ws_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post_id', 'msg_type', 'post_type', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'post_id', 'post_type'], 'integer'],
            [['msg_type'], 'string'],
            [['msg'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 20]
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
            'msg' => 'Msg',
            'post_id' => 'Post ID',
            'msg_type' => 'Msg Type',
            'post_type' => 'Post Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    // public function afterSave($insert, $changedAttributes){
    //     if ($insert) {
    //         $this->post->updateAttributes([
    //             'comment_count' =>  $this->post->comment_count + 1
    //         ]);
    //     }

    //     return parent::afterSave($insert, $changedAttributes);
    // }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

}
