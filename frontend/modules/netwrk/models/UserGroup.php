<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $group_id
 */
class UserGroup extends \yii\db\ActiveRecord
{

    const STATUS_JOINED = 2;
    const STATUS_INVITED = 1;

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'group_id'], 'required'],
            [['user_id', 'group_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Id',
            'group_id' => 'Group Id',
        ];
    }
}
