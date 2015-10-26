<?php 
namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;

class PostController extends BaseController
{   
    private $currentUser = 1;
    public function actionIndex()
    {
        return $this->render('mobile/index');
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
}