<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "feedback_stat".
 *
 * @property integer $id
 * @property integer $points
 * @property integer $ws_message_id
 * @property string $type
 */
class FeedbackStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback_stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['points'], 'required'],
            [['type'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'points' => 'Points',
            'ws_message_id' => 'WS Message ID',
            'type'  => 'Type'
        ];
    }

    public function isFeedbackStat($object, $id)
    {
        $feedback_stat = FeedbackStat::find()->where($object.'_id = :id and type= :type')
            ->addParams(['id'=>$id, 'type'=>$object])
            ->one();

        if ($feedback_stat) {
            return $feedback_stat->id;
        } else {
            return FALSE;
        }
    }
}
