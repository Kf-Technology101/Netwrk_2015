<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
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
            [['city_id', 'user_id', 'post_count', 'view_count','brilliant_count'], 'integer'],
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
            'brilliant_count'=> 'Brilliant Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {   
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['topic_id' => 'id']);
    }

    public function beforeSave($insert){
        if ($insert) {
            $user_exits = Topic::find()->where(['user_id'=> 1,'city_id'=>$this->city_id])->one();
            if(!$user_exits){
                $this->city->updateAttributes([
                    'user_count' =>  $this->city->user_count + 1
                ]);
            }
        }
        return parent::beforeSave($insert);
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
