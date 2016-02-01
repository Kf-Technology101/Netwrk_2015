<?php

namespace frontend\modules\netwrk\models;

use Yii;

class Temp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%temp}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'city'], 'string'],
            [['zipcode', 'lat', 'lng', 'lat_min', 'lat_max','lng_min' , 'lng_max'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zipcode' => 'Zipcode',
            'type' => 'Type',
            'city' => 'City',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'lat_min' => 'Lat Min',
            'lat_max' => 'Lat Max',
            'lng_min' => 'Lng Min',
            'lng_max' => 'Lng Max'
        ];
    }
}
