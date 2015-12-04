<?php

namespace frontend\modules\netwrk\models;

use Yii;
use amnah\yii2\user\models\Profile as BaseProfile;

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
class Profile extends BaseProfile
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
            [['user_id', 'first_name', 'last_name'], 'required'],
            [['user_id', 'age', 'zip_code'], 'integer'],
            [['dob'], 'safe'],
            [['about'], 'string'],
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
}
