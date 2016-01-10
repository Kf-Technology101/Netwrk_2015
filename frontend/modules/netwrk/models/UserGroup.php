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

    const PERMISSION_PUBLIC = 1;
    const PERMISSION_PRIVATE = 2;

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
