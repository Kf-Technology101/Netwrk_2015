<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "user_invitation".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_from
 * @property integer $invitation_code
 * @property integer $used
 */
class UserInvitation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_invitation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_from', 'invitation_code'], 'required'],
            [['user_id', 'user_from', 'used'], 'integer'],
            [['invitation_code'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invitation_code' => 'Invitation Code',
            'user_id' => 'User Id',
            'user_from' => 'User From',
            'used' => 'Used',
        ];
    }
}
