<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "user_key".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $key_value
 * @property string $create_time
 * @property string $consume_time
 * @property string $expire_time
 */
class UserKey extends \yii\db\ActiveRecord
{
    /**
     * @var int Key for email activations (for registrations)
     */
    const TYPE_EMAIL_ACTIVATE = 1;

    /**
     * @var int Key for email changes (=updating account page)
     */
    const TYPE_EMAIL_CHANGE = 2;

    /**
     * @var int Key for password resets
     */
    const TYPE_PASSWORD_RESET = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_key';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'key_value'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['create_time', 'consume_time', 'expire_time'], 'safe'],
            [['key_value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'key_value' => 'Key Value',
            'create_time' => 'Create Time',
            'consume_time' => 'Consume Time',
            'expire_time' => 'Expire Time',
        ];
    }

    public function getUser()
    {
        $user = User::find();
        return $this->hasOne($user::className(), ['id' => 'user_id']);
    }

    /**
     * Generate/reuse a userKey
     *
     * @param int    $userId
     * @param int    $type
     * @param string $expireTime
     * @return static
     */
    public static function generate($userId, $type, $expireTime = null)
    {
        // attempt to find existing record
        // otherwise create new
        $model = static::findActiveByUser($userId, $type);
        if (!$model) {
            $model = new static();
        }

        // set/update data
        $model->user_id     = $userId;
        $model->type        = $type;
        $model->create_time = date("Y-m-d H:i:s");
        $model->expire_time = $expireTime;
        $model->key_value   = Yii::$app->security->generateRandomString();
        $model->save(false);
        return $model;
    }

    /**
     * Find an active userKey by userId
     *
     * @param int       $userId
     * @param array|int $type
     * @return static
     */
    public static function findActiveByUser($userId, $type)
    {
        $now = date("Y-m-d H:i:s");
        return static::find()
            ->where([
                "user_id"      => $userId,
                "type"         => $type,
                "consume_time" => null,
            ])
            ->andWhere("([[expire_time]] >= '$now' or [[expire_time]] is NULL)")
            ->one();
    }

    /**
     * Find an active userKey by key
     *
     * @param string    $key
     * @param array|int $type
     * @return static
     */
    public static function findActiveByKey($key, $type)
    {
        $now = date("Y-m-d H:i:s");
        return static::find()
            ->where([
                "key_value"    => $key,
                "type"         => $type,
                "consume_time" => null,
            ])
            ->andWhere("([[expire_time]] >= '$now' or [[expire_time]] is NULL)")
            ->one();
    }

    /**
     * Consume userKey record
     *
     * @return static
     */
    public function consume()
    {
        $this->consume_time = date("Y-m-d H:i:s");
        $this->save(false);
        return $this;
    }

    /**
     * Expire userKey record
     *
     * @return static
     */
    public function expire()
    {
        $this->expire_time = date("Y-m-d H:i:s");
        $this->save(false);
        return $this;
    }
}
