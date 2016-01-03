<?php

namespace frontend\modules\netwrk\models;

use Yii;

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
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['lat', 'lng', 'post_count', 'user_count','brilliant_count'], 'number'],
            [['name'], 'string', 'max' => 45],
            [['office', 'office_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'office' => 'Office',
            'office_type' => 'Office Type',
            'brilliant_count'=> 'Brilliant Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::className(), ['city_id' => 'id']);
    }

    public function SearchCity($id,$type,$except){
        $limit = Yii::$app->params['LimitResultSearch'];
        if(isset($type) && $type == 'global'){
            return City::find()
                    ->where(['id'=>$id])
                    ->andwhere(['not in','id',$except])
                    ->orderBy(['brilliant_count'=> SORT_DESC])
                    ->limit($limit)
                    ->all();
        }else{
            return City::find()
                    ->where(['id'=> $id])
                    ->orderBy(['brilliant_count'=> SORT_DESC])
                    ->limit($limit)
                    ->all();
        }
    }
}
