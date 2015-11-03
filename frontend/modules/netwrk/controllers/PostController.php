<?php 
namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;

class PostController extends BaseController
{   
    private $currentUser = 1;
    
    public function actionIndex($city,$topic)
    {
        return $this->render($this->getIsMobile() ? 'mobile/index':'',['topic' =>$topic,'city' =>$city]);
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
        $Post->created_at = $current_date;
        $Post->updated_at = $current_date;
        $Post->save();

        $num_top = Post::find()->where('topic_id ='.$topic)->all();
        $top = Topic::findOne($topic);
        // $top->post_count = count($num_top);
        $top->updated_at = $current_date;
        $top->update();
    }

    public function actionGetAllPost(){
        $filter = $_POST['filter'];
        $topic_id = $_POST['topic'];

        $pageSize = $_POST['size'];
        $page = $_POST['page'];

        switch ($filter) {
            case 'post':
                $posts = Post::find()->where('topic_id ='.$topic_id)->orderBy(['comment_count'=> SORT_DESC]);
                break;
            case 'brilliant':
                $posts = Post::find()->where('topic_id ='.$topic_id)->orderBy(['brilliant_count'=> SORT_DESC]);
                break;  
            case 'view':
                $posts = Post::find()->where('topic_id ='.$topic_id)->orderBy(['view_count'=> SORT_DESC]);
                break; 
        }

        $countQuery = clone $posts;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $posts = $posts->offset($pages->offset)->limit($pages->limit)->all();

        $data = [];
        foreach ($posts as $key => $value) {

            $num_view = UtilitiesFunc::ChangeFormatNumber($value->view_count ? $value->view_count : 0);
            $num_comment = UtilitiesFunc::ChangeFormatNumber($value->comment_count ? $value->comment_count : 0);
            $num_brilliant = UtilitiesFunc::ChangeFormatNumber($value->brilliant_count ? $value->brilliant_count : 0);
            $num_date = UtilitiesFunc::FormatDateTime($value->updated_at);

            $content = $value->content;

            if(strlen($content > 80)){
                $content = substr($content,0,80) ;
                $content = $content."...<a class='show_more' href='javascript:void(0)'>show more</a>";
            }

            $user_photo = User::findOne($value->user_id)->profile->photo;

            if ($user_photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                $image = Url::to('@web/uploads/'.$value->user_id.'/'.$user_photo);
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
            );

            array_push($data,$post);
        }

        $temp = array ('status'=> 1 ,'data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }
}