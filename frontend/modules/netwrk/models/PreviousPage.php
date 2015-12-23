<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "ws_messages".
 *
 * @property integer $id
 * @property integer $url
 * @property string $created_at
 * @property string $updated_at
 */
class PreviousPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'previous_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'url', 'created_at', 'updated_at'], 'required'],
            [['id'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 20]
        ];
    }

   /**
     * @inheritdoc
     */
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

}