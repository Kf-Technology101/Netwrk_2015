<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;

class TopicController extends BaseController
{
    public function actionIndex()
    {
// $query = Topic::find()->where(['city_id'=>1])->orderBy(['post_count'=> SORT_DESC]);
// $countQuery = clone $query;
// $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>10, 'page'=>1]);
// $models = $query->offset($pages->offset)
//     ->limit($pages->pageSize)
//     ->all();
        $posts = Post::find()->where('topic_id = 1')->orderBy(['created_at'=> SORT_DESC])->all();
        $num_post = count($posts);
        $data = [];

        foreach ($posts as $key => $post){
            if($key < 3){
                array_push($data,'#'.$post->title);
            }
        }
        $post_data = array(
            'data'=> $data,
            'num_post'=> $num_post - 3
            );
        return $this->render('index');
    }

    public function actionCreateTopic() {
        $city = $_GET['city'];
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/topic/topic-page?city='.$city]);
        }
        $cty = City::findOne($city);
        if ($cty){
            $city_id = $cty->id;
            $name = $cty->name;
            $object = array(
                'city_name'=> $name,
                'status'=> 1
                );
        }else{
            $name = $_GET['name'];
            $zip_code = $_GET['zipcode'];
            $lat = $_GET['lat'];
            $lng = $_GET['lng'];
            $city_id = $city;
            $object = array(
                'status'=> 0,
                'city_name'=> $name,
                'zipcode'=> $zip_code,
                'lat'=> $lat,
                'lng'=> $lng,
                'city_id' => $city_id
                );
        }
        return $this->render('mobile/create',['city'=> $cty ,'city_id' =>$city_id,'data'=> (object)$object]);
    }

    public function actionNewTopic() {
        $currentUser = Yii::$app->user->id;
        $city = $_POST['city'];
        $topic = $_POST['topic'];
        $post = $_POST['post'];
        $message = $_POST['message'];

        $current_date = date('Y-m-d H:i:s');

        $cty = City::findOne($city);
        $city_id = 0;

        if($cty){
            $city_id = $cty->id;
        }else{
            $zipcode = $_POST['zip_code'];
            $city_name = $_POST['netwrk_name'];
            $lat = $_POST['lat'];
            $lng = $_POST['lng'];

            $netwrk = new City;
            $netwrk->name = $city_name;
            $netwrk->lat = $lat;
            $netwrk->lng = $lng;
            $netwrk->zip_code = $zipcode;
            $netwrk->save();
            $city_id = $netwrk->id;
        }

        $Topic = new Topic();
        $Topic->user_id = $currentUser;
        $Topic->city_id = $city_id;
        $Topic->title = $topic;
        $Topic->save();

        $Post = new Post();
        $Post->title = $post;
        $Post->content = $message;
        $Post->topic_id = $Topic->id;
        $Post->user_id = $currentUser;
        $Post->post_type = 1;
        $Post->save();

        $Topic->post_count = 1;
        $Topic->update();

        return $city_id;
    }

    public function actionGetTopicMobile()
    {
        $city = $_GET['city'];
        $filter = $_GET['filter'];
        $pageSize = $_GET['size'];
        $page = $_GET['page'];
        $cty = City::findOne($city);
        if(!$cty){
            $zipcode = $_GET['zipcode'];
        }
        switch ($filter) {
            case 'recent':
            $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['created_at'=> SORT_DESC]);
            break;
            case 'post':
            $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['post_count'=> SORT_DESC]);
            break;
            case 'view':
            $topices = Topic::find()->where(['city_id'=>$city])->orderBy(['view_count'=> SORT_DESC]);
            break;
        }

        $countQuery = clone $topices;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $topices = $topices->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        $data = [];
        foreach ($topices as $key => $value) {
            $num_view = UtilitiesFunc::ChangeFormatNumber($value->view_count);
            $num_post = UtilitiesFunc::ChangeFormatNumber($value->post_count - 3);
            $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
            $posts = Post::find()->where('topic_id ='.$value->id)->orderBy(['created_at'=> SORT_DESC])->all();
            $data_post = [];

            foreach ($posts as $key => $post){
                if($key < 3){
                    array_push($data_post,'#'.$post->title);
                }
            }
            $post_data = array(
                'data_post'=> $data_post,
                );

            $topic = array(
                'id'=> $value->id,
                'city_id'=>$value->city_id,
                'title'=>$value->title,
                'post_count' => $value->post_count > 0 ? $value->post_count: 0,
                'post_count_format' => $num_post,
                'view_count'=> $num_view > 0 ? $num_view : 0,
                'img'=> Url::to('@web/img/icon/timehdpi.png'),
                'created_at'=>$num_date,
                'post'=> $post_data
                );
            array_push($data,$topic);
        }

        $temp = array ('data'=> $data ,'city' => $cty ? $cty->zip_code : $zipcode);
        $hash = json_encode($temp);
        return $hash;
    }

    public function actionTopicPage() {
        $city = $_GET['city'];

        $object = array();

        $cty = City::findOne($city);
        if ($cty){
            $city_id = $cty->id;
            $name = $cty->name;
            $object = array(
                'city_name'=> $name,
                'zipcode'=> $cty->zip_code,
                'status'=> 1
                );
        }else{

            $name = $_GET['name'];
            $zip_code = $_GET['zipcode'];
            $lat = $_GET['lat'];
            $lng = $_GET['lng'];
            $city_id = $city;
            $object = array(
                'status'=> 0,
                'city_name'=> $name,
                'zipcode'=> $zip_code,
                'lat'=> $lat,
                'lng'=> $lng,
                'city_id' => $city_id
                );
        }
        return $this->render('mobile/index', ['city_id' =>$city_id,'data'=> (object)$object]);
    }

    public function actionUpdateViewTopic(){
        $id = $_POST['topic'];

        $topic = Topic::findOne($id);
        $topic->view_count ++;
        $topic->update();
    }

    public function actionGetTopic(){
        $id = $_POST['topic'];
        $topic = Topic::findOne($id);
        return json_encode(['title'=>$topic->title,'zipcode'=>$topic->city->zip_code]);
    }

    /**
     * [Function is used to get  List out ALL created Topic/ Post within that netwrk, 3 posts within that netwrk and have most number of users joining the post discussion, 3 topics which have most posts within this zip code]
     * @param  $zipcode
     * @return array      [data of json]
     */
    public function actionGetFeed() {
        $city = isset($_GET['city']) && $_GET['city'] != null ? $_GET['city'] : null;
        $request = Yii::$app->request->isAjax;
        if($request){
            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            $top_post = Post::GetTopPostUserJoinGlobal($limit, $city);
            $top_topic = Topic::GetTopTopicGlobal($limit, $city);
            $top_city = City::GetTopCityUserJoinGlobal($limit, $city);
            $feed = [];
            $query = new Query();
            $feed_post = $query->select('post.id,post.title,post.content, post.user_id, profile.photo as photo, post.created_at as created_at, profile.first_name, profile.last_name, topic.id as topic_id, topic.title as topic_title, city.zip_code as zip_code')
                       ->from('post')
                       ->innerJoin('profile','post.user_id = profile.user_id')
                       ->innerJoin('topic', 'post.topic_id=topic.id')
                       ->innerJoin('city', 'topic.city_id=city.id')
                       ->where(['not',['post.topic_id'=> null]])
                       ->andWhere('topic.city_id = '.$city)
                       ->orderBy(['post.created_at'=> SORT_DESC])
                       ->all();

            foreach ($feed_post as $key => $value) {
                $url_avatar = User::GetUrlAvatar($value['user_id'],$value['photo']);
                $value['photo'] = $url_avatar;
                $num_date = UtilitiesFunc::FormatDateTime($value['created_at']);
                $value['appear_day'] = $num_date;
                $value['posted_by'] = $value['first_name'] ." ".$value['last_name'];
                array_push($feed, $value);
            }

            $feed_topic = Topic::find()
                    ->where('city_id ='.$city)
                    ->with('user')
                    ->orderBy(['topic.created_at'=> SORT_DESC])
                    ->all();

            foreach ($feed_topic as $key => $value) {
                $num_date = UtilitiesFunc::FormatDateTime($value['created_at']);
                $item = [
                    'id' => $value->id,
                    'title'=> $value->title,
                    'city_id'=> $value->city_id,
                    'city_name'=> $value->city->name,
                    'created_at' => $value->created_at,
                    'appear_day' => $num_date,
                    'created_by' => $value['user']['profile']['first_name']." ".  $value['user']['profile']['last_name'],
                ];
                array_push($feed, $item);
            }
            usort($feed, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            $item = [
                'top_post'=> $top_post,
                'top_topic'=> $top_topic,
                'feed' => $feed
            ];

            $hash = json_encode($item);
            return $hash;
        }
    }
}