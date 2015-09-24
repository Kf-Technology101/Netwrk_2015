<?php

namespace frontend\modules\netwrk\models;

use Yii;

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
}
