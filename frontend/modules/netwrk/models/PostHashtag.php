<?php

namespace frontend\modules\netwrk\models;

use Yii;

/**
 * This is the model class for table "post_hashtag".
 *
 * @property integer $id
 * @property integer $hashtag_id
 * @property integer $post_id
 */
class PostHashtag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_hashtag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hashtag_id', 'post_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hashtag_id' => 'Hashtag ID',
            'post_id' => 'Post ID',
        ];
    }

    public function getHashtag()
    {
        return $this->hasOne(Hashtag::className(), ['id' => 'hashtag_id']);
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function afterSave($insert, $changedAttributes){
        if($insert){
            $this->UpdateHashTag();
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function UpdateHashTag(){
        $count_hashtag = PostHashtag::find()->where(['hashtag_id'=>$this->hashtag_id])->count();
        $model = Hashtag::findOne($this->hashtag_id);
        if($model){
            $model->count_total = $count_hashtag;
            $model->save(false);
        }
    }

    public function createPostHashtag($hashtag,$post){
        $post_hashtag = PostHashtag::findPostHashtag($hashtag,$post);
        if(!$post_hashtag){
            $model = new PostHashtag();
            $model->post_id = $post;
            $model->hashtag_id = $hashtag;
            $model->save(false);
        }
    }

    public function findPostHashtag($hashtag,$post){
        return PostHashtag::find()->where(['hashtag_id'=> $hashtag,'post_id'=>$post])->one();
    }
}
