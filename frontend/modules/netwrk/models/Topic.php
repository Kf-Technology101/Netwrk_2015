<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "topic".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $user_id
 * @property string $title
 * @property integer $post_count
 * @property integer $view_count
 * @property string $created_at
 * @property string $updated_at
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'user_id', 'title'], 'required'],
            [['city_id', 'user_id', 'post_count', 'view_count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'post_count' => 'Post Count',
            'view_count' => 'View Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
