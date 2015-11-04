<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "vote".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $user_id
 * @property string $created_at
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'user_id'], 'required'],
            [['post_id', 'user_id'], 'integer'],
            [['created_at'], 'safe']
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
            'created_at' => 'Created At',
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function afterSave($insert, $changedAttributes){
        if ($insert) {
            $this->post->updateAttributes([
                'brilliant_count' =>  $this->post->brilliant_count + 1
            ]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}
