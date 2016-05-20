<?php

namespace frontend\modules\netwrk\models;

use Yii;
use yii\base\Behavior;
use yii\db\Query;
use yii\db\ActiveRecord;
use frontend\modules\netwrk\models\WsMessages;
use frontend\modules\netwrk\models\User;
/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $topic_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'user_id'], 'required'],
            [['content'], 'string'],
            [['topic_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['post_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'topic_id' => 'Topic ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'post_type' => 'Post Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }

    public function getWsMessages()
    {
        return $this->hasMany(WsMessages::className(), ['post_id' => 'id']);
    }

    public function getPostHashtag()
    {
        return $this->hasMany(PostHashtag::className(), ['post_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes){
        if ($insert) {
            if($this->post_type == 1) {
                $this->topic->updateCounters(['post_count' => 1]);
                $this->updateCounters(['comment_count' => 1]);
                if (!empty($this->topic->city)) {
                    $this->topic->city->updateAttributes([
                        'post_count' => $this->topic->city->post_count + 1
                    ]);
                    $this->CreateFirstMessage($this);
                    $this->CreateHashtag($this);
                    $this->CreaetHistoryFeed($this);
                }

            }

        }else{
            $this->updateAttributes(['view_count' => $this->view_count + 1]);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function CreaetHistoryFeed($post){
        $hfp = new HistoryFeed();
        $hfp = new HistoryFeed();
        $hfp->id_item = $post->id;
        $hfp->type_item = 'post';
        $hfp->city_id = $post->topic->city_id;
        $hfp->created_at = $post->created_at;
        $hfp->save(false);
    }

    public function CreateHashtag($post){
        $arr = explode(' ',trim($post->title));

        $hashtag = Hashtag::findHashtag($arr[0]);
        PostHashtag::createPostHashtag($hashtag->id,$post->id);
    }

    public function CreateFirstMessage($post){
        $msg = new WsMessages();
        $msg->user_id = $post->user_id;
        $msg->post_id = $post->id;
        $msg->post_type = 1;
        $msg->msg_type = 1;
        $msg->msg = $post->content;
        $msg->save(false);
    }

    public function afterDelete(){

        $this->topic->updateAttributes(['post_count' => $this->topic->post_count > 0 ? $this->topic->post_count - 1 : 0]);
        $this->topic->city->updateAttributes([
            'post_count' =>  $this->topic->city->post_count > 0 ? $this->topic->city->post_count - 1 : 0
        ]);

        return parent::afterDelete();
    }

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

    public function SearchPost($_search,$type,$except){
        $limit = Yii::$app->params['LimitResultSearch'];
        if(isset($type) && $type == 'global'){
            return Post::find()->joinWith('topic')
                    ->where(['like','post.title',$_search])
                    ->andWhere(['not in','topic.city_id',$except])
                    ->andWhere(['not',['topic_id'=> NULL]])
                    ->orderBy(['brilliant_count'=> SORT_DESC])
                    ->limit($limit)
                    ->all();
        }else{
            return Post::find()
                    ->where(['like','title',$_search])
                    ->andWhere(['not',['topic_id'=> NULL]])
                    ->orderBy(['brilliant_count'=> SORT_DESC])
                    ->all();
        }
    }

    public function SearchHashTagPost($hashtag,$city){
        $hashtag = Post::find()
                        ->joinWith('topic')
                        ->where(['like','post.title',$hashtag])
                        ->andWhere(['topic.city_id' => $city])
                        ->all();
        return count($hashtag);
    }

    public function GetPostMostBrilliant($city){
        $post = Post::find()
                ->joinWith('topic')
                ->where(['topic.city_id' => $city])
                ->orderBy(['post.brilliant_count'=> SORT_DESC])
                ->one();
        return $post;
    }

    public function CountUserJoinPost($post){
        $query = new Query();
        $datas = $query->select('*,COUNT(DISTINCT ws_messages.user_id) AS count_user_comment')
            ->from('post')
            ->where(['post.id'=>$post])
            ->leftJoin('ws_messages', 'post.id=ws_messages.post_id')
            ->orderBy('count_user_comment DESC')
            ->one();
        return $datas['count_user_comment'];
    }

    public function GetTopPostUserJoinGlobal($limit, $city, $city_ids = null){
        $query = new Query();
        $maxlength = Yii::$app->params['MaxlenghtContentLanding'];
        $maxlengthMobile = Yii::$app->params['MaxlenghtMessageMobile'];
        if ($city != null) {
            $data = $query ->select('post.id,post.title,post.content,post.brilliant_count,ws_messages.user_id,profile.photo, topic.id as topic_id, topic.title as topic_title, city.zip_code, count(DISTINCT ws_messages.user_id) as user_join')
                       ->from('ws_messages')
                       ->leftJoin('profile','ws_messages.user_id = profile.user_id')
                       ->innerJoin('post', 'post.id=ws_messages.post_id')
                       ->innerJoin('topic', 'post.topic_id=topic.id')
                       ->innerJoin('city', 'topic.city_id=city.id')
                       ->where(['not',['post.topic_id'=> null]])
                       ->andWhere('topic.city_id = '.$city)
                       ->groupBy('post.id')
                       ->orderBy('user_join DESC')
                       ->limit($limit)
                       ->all();
        } else {
            // If state is not null then get top post user join within that state
            if($city_ids != null) {
                $data = $query ->select('post.id,post.title,post.content,post.brilliant_count,ws_messages.user_id,profile.photo, topic.id as topic_id, topic.title as topic_title, city.zip_code, count(DISTINCT ws_messages.user_id) as user_join')
                    ->from('ws_messages')
                    ->leftJoin('profile','ws_messages.user_id = profile.user_id')
                    ->innerJoin('post', 'post.id=ws_messages.post_id')
                    ->innerJoin('topic', 'post.topic_id=topic.id')
                    ->innerJoin('city', "(topic.city_id = city.id AND city.id IN (".$city_ids."))")
                    ->where(['not',['post.topic_id'=> null]])
                    ->groupBy('post.id')
                    ->orderBy('user_join DESC')
                    ->limit($limit)
                    ->all();
            } else {
                $data = $query ->select('post.id,post.title,post.content,post.brilliant_count,ws_messages.user_id,profile.photo,count(DISTINCT ws_messages.user_id) as user_join')
                    ->from('ws_messages')
                    ->leftJoin('profile','ws_messages.user_id = profile.user_id')
                    ->leftJoin('post', 'post.id=ws_messages.post_id')
                    ->where(['not',['post.topic_id'=> null]])
                    ->groupBy('post.id')
                    ->orderBy('user_join DESC')
                    ->limit($limit)
                    ->all();
            }
        }

        foreach ($data as $key => $value) {
            # code...
            $url_avatar = User::GetUrlAvatar($value['user_id'],$value['photo']);
            $data[$key]['photo'] = $url_avatar;

            #minimize content
            $content = $value['content'];
            if($this->getIsMobile() && strlen($content) > $maxlengthMobile){
                $content = substr($content,0,$maxlengthMobile) ;
                $content = $content." ...<span class='show_more'>show more</span>";
            }elseif(!$this->getIsMobile() && strlen($content) > $maxlength){
                $content = substr($content,0,$maxlength) ;
                $content = $content." ...<span class='show_more'>show more</span>";
            }
            $data[$key]['content'] = $content;
        }
        return $data;
    }

    public function GetHQPostGlobal($city){
        $query = new Query();
        if ($city != null) {
            $data = $query ->select('post.id as post_id, post.title, post.content as post_content, post.user_id as user_id, topic.id as topic_id, city.id as city_id')
                ->from('post')
                ->innerJoin('topic', 'post.topic_id=topic.id')
                ->innerJoin('city', 'topic.city_id=city.id')
                ->where(['not',['post.topic_id'=> null]])
                ->andWhere('topic.city_id = '.$city)
                ->andWhere('topic.title = "Main Channel"')
                ->one();
        }

        return $data;
    }
}
