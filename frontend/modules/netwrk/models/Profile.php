<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $dob
 * @property integer $age
 * @property string $work
 * @property string $photo
 * @property string $about
 * @property string $gender
 * @property integer $zip_code
 * @property double $lat
 * @property double $lng
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name','dob','gender'], 'required'],
            [['user_id', 'age'], 'integer'],
            [['dob'], 'safe'],
            [['about','dob'], 'string'],
            [['lat', 'lng'], 'number'],
            [['first_name', 'last_name', 'work'], 'string', 'max' => 45],
            [['photo', 'gender'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'dob' => 'Dob',
            'age' => 'Age',
            'work' => 'Work',
            'photo' => 'Photo',
            'about' => 'About',
            'gender' => 'Gender',
            'zip_code' => 'Zip Code',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }

    // public function behaviors()
    // {
    //     return [
    //         'timestamp' => [
    //             'class'      => 'yii\behaviors\TimestampBehavior',
    //             'value'      => function () { return date("Y-m-d H:i:s"); },
    //             'attributes' => [
    //                 ActiveRecord::EVENT_BEFORE_INSERT => 'create_time',
    //                 ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
    //             ],
    //         ],
    //     ];
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        $user = Yii::$app->getModule("netwrk")->model("User");
        return $this->hasOne($user::className(), ['id' => 'user_id']);
    }

    /**
     * Set user id
     *
     * @param int $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->user_id = $userId;
        return $this;
    }
}
