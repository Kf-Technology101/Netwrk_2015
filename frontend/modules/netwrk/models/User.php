<?php

namespace frontend\modules\netwrk\models;

use Yii;
use amnah\yii2\user\models\User as BaseUser;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $status
 * @property string $email
 * @property string $new_email
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $api_key
 * @property string $login_ip
 * @property string $login_time
 * @property string $create_ip
 * @property string $create_time
 * @property string $update_time
 * @property string $ban_time
 * @property string $ban_reason
 */
class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'status'], 'required'],
            [['role_id', 'status'], 'integer'],
            [['login_time', 'create_time', 'update_time', 'ban_time'], 'safe'],
            [['email', 'new_email', 'username', 'password', 'auth_key', 'api_key', 'login_ip', 'create_ip', 'ban_reason'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'status' => 'Status',
            'email' => 'Email',
            'new_email' => 'New Email',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'api_key' => 'Api Key',
            'login_ip' => 'Login Ip',
            'login_time' => 'Login Time',
            'create_ip' => 'Create Ip',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'ban_time' => 'Ban Time',
            'ban_reason' => 'Ban Reason',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getSetting()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'id']);
    }
    // public function getPostes()
    // {
    //     return $this->hasMany(Post::className(), ['user_id' => 'id']);
    // }
}
