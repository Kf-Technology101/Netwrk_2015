<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Vote;
use frontend\modules\netwrk\models\WsMessages;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;

class PostController extends BaseController
{
    private $currentUser = 1;

    public function actionResetPostCount(){
        $posts = Post::find()->all();
        foreach ($posts as $key => $value) {
            $value->brilliant_count = null;
            $value->save();
        }
    }

    public function actionIndex()
    {
        $topic_id = $_GET['topic'];
        $topic= Topic::find()->where('id ='.$topic_id)->one();
        return $this->render($this->getIsMobile() ? 'mobile/index':'',['topic' =>$topic,'city' =>$topic->city->id]);
    }

    public function actionCreatePost($city,$topic)
    {
        $top = Topic::findOne($topic);
        $cty = City::findOne($city);
        return $this->render('mobile/create',['topic' =>$top,'city' =>$cty]);
    }

    public function actionNewPost()
    {
        // $city = $_POST['city'];
        $topic = $_POST['topic'];
        $post = $_POST['post'];
        $message = $_POST['message'];
        $current_date = date('Y-m-d H:i:s');

        $Post = new Post;
        $Post->title = $post;
        $Post->content = $message;
        $Post->topic_id = $topic;
        $Post->user_id = $this->currentUser;
        $Post->save();

    }

    public function actionGetAllPost(){

        $maxlength = Yii::$app->params['MaxlenghtMessageDesktop'];
        $maxlengthMobile = Yii::$app->params['MaxlenghtMessageMobile'];

        $filter = $_POST['filter'];
        $topic_id = $_POST['topic'];

        $pageSize = $_POST['size'];
        $page = $_POST['page'];

        switch ($filter) {
            case 'post':
            $condition = 'created_at';
            break;
            case 'brilliant':
            $condition = 'brilliant_count';
            break;
            case 'view':
            $condition = 'view_count';
            break;
        }

        $posts = Post::find()->where('topic_id ='.$topic_id)->with('topic')->orderBy([$condition=> SORT_DESC]);
        $pages = new Pagination(['totalCount' => $posts->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $posts = $posts->offset($pages->offset)->limit($pages->limit)->all();

        $data = [];
        foreach ($posts as $key => $value) {

            $num_view = UtilitiesFunc::ChangeFormatNumber($value->view_count ? $value->view_count : 0);
            $num_comment = UtilitiesFunc::ChangeFormatNumber($value->comment_count ? $value->comment_count + 1 : 1);
            $num_brilliant = UtilitiesFunc::ChangeFormatNumber($value->brilliant_count ? $value->brilliant_count : 0);
            $num_date = UtilitiesFunc::FormatDateTime($value->created_at);

            $content = $value->content;

            if($this->getIsMobile() && strlen($content) > $maxlengthMobile){
                $content = substr($content,0,$maxlengthMobile) ;
                $content = $content." ...<span class='show_more'>show more</span>";
            }elseif(!$this->getIsMobile() && strlen($content) > $maxlength){
                $content = substr($content,0,$maxlength) ;
                $content = $content." ...<span class='show_more'>show more</span>";
            }

            $user_photo = User::findOne($value->user_id)->profile->photo;

            if ($user_photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                $image = Url::to('@web/uploads/'.$value->user_id.'/'.$user_photo);
            }

            $currentVote = Vote::find()->where('user_id= '.$this->currentUser.' AND post_id= '.$value->id)->one();

            if($currentVote && $currentVote->status == 1){
                $isVote = 1;
            }else{
                $isVote = 0;
            }

            $post = array(
                'id'=> $value->id,
                'topic_name'=> $value->topic->title,
                'topic_id'=>$value->topic_id,
                'title'=>$value->title,
                'content'=>$content,
                'num_view' => $num_view > 0 ? $num_view : 0,
                'num_comment' => $num_comment ? $num_comment: 0,
                'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                'avatar'=> $image,
                'update_at'=>$num_date,
                'is_vote'=> $isVote
                );

            array_push($data,$post);
        }

        $temp = array ('status'=> 1 ,'data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }

    public function actionVotePost(){
        $post_id = $_POST['post_id'];

        $current_date = date('Y-m-d H:i:s');
        $curVote = Vote::find()->where('user_id = '.$this->currentUser.' AND post_id = '.$post_id)->one();

        if($curVote){
            if($curVote->status == 1){
                $curVote->status = 0;
                $curVote->save();
            }else{
                $curVote->status = 1;
                $curVote->save();
            }
        }else{
            $vote = new Vote;
            $vote->post_id = $post_id;
            $vote->user_id = $this->currentUser;
            $vote->status = 1;
            $vote->created_at = $current_date;
            $vote->save();
        }

        $post = Post::findOne($post_id);
        $temp = array ('status'=> 1 ,'data'=> $post->brilliant_count);
        $hash = json_encode($temp);

        return $hash;
    }

    public function actionGetChatInbox()
    {
        $messages = new WsMessages();
        $messages = $messages->find()->select('post_id')->where('user_id = '.$this->currentUser)
        ->distinct()
        ->with('post')
        ->all();

        if($messages) {
            $data = [];

            foreach ($messages as $key => $message) {
                $user_photo = User::findOne($message->post->user_id)->profile->photo;
                if ($user_photo == null){
                    $image = 'img/icon/no_avatar.jpg';
                }else{
                    $image = 'uploads/'.$message->post->user_id.'/'.$user_photo;
                }

                $currentVote = Vote::find()->where('user_id= '.$this->currentUser.' AND post_id= '.$message->post->id)->one();

                $num_comment = UtilitiesFunc::ChangeFormatNumber($message->post->comment_count ? $message->post->comment_count + 1 : 1);
                $num_brilliant = UtilitiesFunc::ChangeFormatNumber($message->post->brilliant_count ? $message->post->brilliant_count : 0);
                $num_date = UtilitiesFunc::FormatDateTime($message->post->updated_at ? $message->post->updated_at : $message->post->created_at);

                $item = [
                    'id'=> $message->post->id,
                    'post_title'=> $message->post->title,
                    'post_content'=> $message->post->content,
                    'topic_id'=> $message->post->topic_id,
                    'title'=> $message->post->title,
                    'content'=> $message->post->content,
                    'num_comment' => $num_comment ? $num_comment: 0,
                    'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                    'avatar'=> $image,
                    'update_at'=> $num_date,
                    'real_update_at' => $message->post->updated_at ? $message->post->updated_at : $message->post->created_at
                    ];
                array_push($data, $item);
            }
            // return strtotime($data[0]['real_update_at']) - strtotime($data[1]['real_update_at']);die;
            usort($data, function($a, $b) {
                return strtotime($b['real_update_at']) - strtotime($a['real_update_at']);
            });
            $data = json_encode($data);
            return $data;
        } else {
            return false;
        }
    }
}