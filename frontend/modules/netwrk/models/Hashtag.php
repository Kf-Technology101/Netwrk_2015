<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "hashtag".
 *
 * @property integer $id
 * @property string $hashtag
 * @property integer $count_total
 * @property string $created_at
 * @property string $updated_at
 */
class Hashtag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hashtag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hashtag', 'created_at'], 'required'],
            [['hashtag'], 'string'],
            [['count_total'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hashtag' => 'Hashtag',
            'count_total' => 'Count Total',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public function getPostHashtag()
    {
        return $this->hasOne(PostHashtag::className(), ['hashtag_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes){
        return parent::afterSave($insert, $changedAttributes);
    }

    //find hashtag with name
    public function findHashtag($hashtag){
        $tag = Hashtag::find()->where(['hashtag'=>$hashtag])->one();

        if($tag){
            return $tag;
        }else{
            $model = new Hashtag();
            $model->hashtag = $hashtag;
            $model->save(false);
            return $model;
        }
    }

    // top hastag in City
    public function TopHashtagInCity($city,$limit){
        $query = new Query();
        $model = $query->select('count(hashtag.id) as count_hash, hashtag.id,hashtag.hashtag,')
                    ->from('hashtag')
                    ->leftJoin('post_hashtag', 'post_hashtag.hashtag_id = hashtag.id')
                    ->leftJoin('post', 'post_hashtag.post_id = post.id')
                    ->leftJoin('topic', 'topic.id = post.topic_id')
                    ->where(['topic.city_id' => $city])
                    ->groupBy('hashtag.id')
                    ->orderBy('count_hash DESC')
                    ->limit($limit)
                    ->all();
        return $model;
    }
}
