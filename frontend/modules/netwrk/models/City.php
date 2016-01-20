<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\Query;
use frontend\modules\netwrk\models\WsMessages;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\Hashtag;

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

    //Get top netwrk have most user
    public function GetTopCityUserJoinGlobal($limit){
        $query = new Query();
        $data = $query ->select('c.id as city_id ,c.name as city_name, c.office as office_name,c.zip_code as zip_code,t.id as topic_id,t.title as topic_name ,p.id as post_id,ws.msg, count( DISTINCT ws.user_id) as user_join')
                       ->from('ws_messages ws')
                       ->leftJoin('post p', 'p.id=ws.post_id')
                       ->leftJoin('topic t', 't.id=p.topic_id')
                       ->leftJoin('city c','c.id = t.city_id')
                       ->where(['not',['p.topic_id'=> null]])
                       ->groupBy('c.id')
                       ->orderBy('user_join DESC')
                       ->limit($limit)
                       ->all();
        return $data;
    }

    //Get tophashtag each top city
    public function TopHashTag_City($city,$limit){

        foreach ($city as $key => $value) {
            # code...
            $model_hashtag = Hashtag::TopHashtagInCity($value['city_id'],$limit);
            $city[$key]['top_hashtag'] = $model_hashtag;
        }

        return $city;
    }
}
