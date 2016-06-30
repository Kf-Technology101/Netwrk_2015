<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\Query;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use frontend\modules\netwrk\models\WsMessages;
/**
 * This is the model class for table "group".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $city_id
 * @property double $latitude
 * @property double $longitude
 * @property string $name
 * @property integer $permission
 * @property string $created_at
 * @property string $updated_at
 */
class Group extends \yii\db\ActiveRecord
{

    const PERMISSION_PUBLIC = 1;
    const PERMISSION_PRIVATE = 2;

    public function getTopic() {
        return $this->hasMany(Topic::className(), ['group_id' => 'id']);
    }

    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'permission'], 'required'],
            [['name'], 'string'],
            [['permission', 'user_id'], 'integer'],
            [['latitude', 'longitude'], 'double'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'user_id' => 'User ID',
            'city_id' => 'City ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'permission' => 'Permission',
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

    public function GetInvitedGroupIdByUser($user_id) {
        $query = new Query();

        if($user_id != null) {
            $data = $query ->select('g.group_id as group_id')
                ->from('user_group g')
                ->where("g.user_id = ".$user_id)
                ->one();

            return $data['group_id'];
        }

        return false;
    }
}
