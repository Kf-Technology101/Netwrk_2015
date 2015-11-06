<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
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
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'user_id'], 'required'],
            [['content'], 'string'],
            [['topic_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'topic_id' => 'Topic ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }



    public function afterSave($insert, $changedAttributes){
        if ($insert) {
            // var_dump($this);
            $this->topic->updateCounters(['post_count' => 1]);
            $this->topic->city->updateAttributes([
                'post_count' =>  $this->topic->city->post_count + 1
            ]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete(){
        
        $this->topic->updateAttributes(['post_count' => $this->topic->post_count > 0 ? $this->topic->post_count - 1 : 0]);
        $this->topic->city->updateAttributes([
            'post_count' =>  $this->topic->city->post_count > 0 ? $this->topic->city->post_count - 1 : 0
        ]);
       
        return parent::afterDelete();
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
