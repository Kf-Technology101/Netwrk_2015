<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

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

    public function afterSave($insert, $changedAttributes){
        return parent::afterSave($insert, $changedAttributes);
    }

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
}
