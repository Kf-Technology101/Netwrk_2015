<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Favorite;
use frontend\modules\netwrk\models\Group;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\HistoryFeed;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;
use yii\base\Exception;

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
            if ($cty->office == 'Ritchey Woods Nature Preserve') {
                $cty->zip_code = 'Netwrk hq';
            }
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

        $Topic = new Topic;

        if (empty($_POST['byGroup']) || $_POST['byGroup'] == "false") {
            $city = $_POST['city'];
            $cty = City::findOne($city);
            $city_id = 0;
            if ($cty) {
                $city_id = $cty->id;
            } else {
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
            $Topic->city_id = $city_id;
        } else {
            $groupId = $_POST['group'];
            if (empty($groupId)) throw new Exception("Group is empty");
            $group = Group::find()->where(array("id" => $groupId))->one();
            if (empty($group)) throw new Exception("Group not found");
            $Topic->group_id = $groupId;
            $city_id = $group->city_id;
        }

        $Topic->user_id = $currentUser;
        $Topic->title = $topic;
        $Topic->save();

        $hft = new HistoryFeed();
        $hft->id_item = $Topic->id;
        $hft->type_item = 'topic';
        $hft->city_id = $Topic->city_id;
        $hft->created_at = $Topic->created_at;
        $hft->save();

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
        $filter = $_GET['filter'];
        $pageSize = $_GET['size'];
        $page = $_GET['page'];

        $where = array();
        if (!empty($_GET['city'])) {
            $city = $_GET['city'];
            $cty = City::findOne($city);
            $where['city_id'] = $_GET['city'];
            if ($cty->office == 'Ritchey Woods Nature Preserve') {
                $cty->zip_code = 'Netwrk hq';
            }
        }
        if (!empty($cty) && !$cty) {
            $zipcode = $_GET['zipcode'];
        }

        if (!empty($cty)) {
            $is_favorite = Favorite::isFavoritedByUser('city', $cty->id);
        } else {
            $is_favorite = false;
        }

        if (!empty($_GET['group'])) {
            $where['group_id'] = $_GET['group'];
        }

        if (empty($where)) {
            throw new Exception("wrong parametres");
        }

        switch ($filter) {
            case 'recent':
            $topices = Topic::find()->where($where)->orderBy(['created_at'=> SORT_DESC]);
            break;
            case 'post':
            $topices = Topic::find()->where($where)->orderBy(['post_count'=> SORT_DESC]);
            break;
            case 'view':
            $topices = Topic::find()->where($where)->orderBy(['view_count'=> SORT_DESC]);
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

        $temp = array('data' => $data, 'is_favorite' => $is_favorite);
        if (!empty($cty)) {
            $temp['city'] = ($cty ? $cty->zip_code : $zipcode);
            $temp['city_id'] = ($cty ? $cty->id : '');
        }
        $hash = json_encode($temp);
        return $hash;
    }

    public function actionTopicPage() {
        $city = $_GET['city'];
        $title = @Yii::$app->request->get('title');

        $object = array();

        $cty = City::findOne($city);
        if ($cty){
            if ($cty->office == 'Ritchey Woods Nature Preserve') {
                $title = 'Netwrk hq';
            }
            $city_id = $cty->id;
            $name = $cty->name;
            $object = array(
                'city_name'=> $name,
                'zipcode'=> $cty->zip_code,
                'status'=> 1,
                'title' => $title
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
        $pageSize = isset($_GET['size']) && $_GET['size'] != null ? $_GET['size'] : null;
        $page = isset($_GET['page']) && $_GET['page'] != null ? $_GET['page'] : null;
        $request = Yii::$app->request->isAjax;
        if($request){
            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            $top_post = Post::GetTopPostUserJoinGlobal($limit, $city);
            $top_topic = Topic::GetTopTopicGlobal($limit, $city);
            $top_city = City::GetTopCityUserJoinGlobal($limit, $city);

            $htf = new HistoryFeed();
            $query_feed = $htf->find()->where('city_id = '. $city)->orderBy(['created_at'=> SORT_DESC]);
            $countQuery = clone $query_feed;
            $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
            $data_feed = $query_feed->offset($pages->offset)
                                    ->limit($pages->limit)
                                    ->all();
            $feeds =[];
            foreach ($data_feed as $key => $value) {
                if ($value->type_item == 'post') {
                    $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
                    $url_avatar = User::GetUrlAvatar($value->item->user->id,$value->item->user->profile->photo);
                    $item = [
                        'id' => $value->item->id,
                        'title'=> $value->item->title,
                        'content'=> $value->item->content,
                        'topic_id' => $value->item->topic_id,
                        'photo' => $url_avatar,
                        'city_id'=> $value->item->topic->city_id,
                        'city_name'=> $value->item->topic->city->name,
                        'created_at' => $value->created_at,
                        'appear_day' => $num_date,
                        'posted_by' => $value->item->user['profile']['first_name']." ". $value->item->user['profile']['last_name'],
                        'user_id' => $value->item->user_id,
                        'is_post' => 1
                        ];
                } else {
                    $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
                    $item = [
                        'id' => $value->item->id,
                        'title'=> $value->item->title,
                        'city_id'=> $value->item->city_id,
                        'city_name'=> $value->item->city->name,
                        'created_at' => $value->created_at,
                        'appear_day' => $num_date,
                        'created_by' => $value->item->user['profile']['first_name']." ".$value->item->user['profile']['last_name'],
                        'is_post' => 0
                        ];
                }
                array_push($feeds, $item);
            }

            $item = [
                'top_post'=> $top_post,
                'top_topic'=> $top_topic,
                'feed' => $feeds
            ];

            $hash = json_encode($item);
            return $hash;
        }
    }

    public function actionGetTopicsByUser()
    {
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $pageSize = isset($_GET['size']) ? $_GET['size'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : '';

        $currentUserId = isset($_GET['user']) ? $_GET['user'] : Yii::$app->user->id;

        $where['user_id'] = $currentUserId;

        if (empty($currentUserId)) {
            throw new Exception("wrong parametres");
        }

        switch ($filter) {
            case 'recent':
                $topices = Topic::find()->where($where)->orderBy(['created_at'=> SORT_DESC]);
                break;
            default:
                $topices = Topic::find()->where($where)->orderBy(['created_at'=> SORT_DESC]);
                break;
        }

        $countQuery = clone $topices;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $totalCount,'pageSize'=>$pageSize,'page'=> $page - 1]);
        $topices = $topices->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $data = [];
        foreach ($topices as $key => $value) {
            $topic = array(
                'id'=> $value->id,
                'city_id'=>$value->city_id,
                'city_name'=> $value->city->name,
                'title'=>$value->title,
                'img'=> Url::to('@web/img/icon/timehdpi.png'),
                'created_at'=>$value->created_at,
                'formatted_created_date' => date('M d', strtotime($value->created_at)),
                'formatted_created_date_month_year' => date('F Y', strtotime($value->created_at))
            );
            array_push($data,$topic);
        }

        //Grouped activity in month
        $topicArray = array();
        foreach ($data as $item) {
            $topicArray[$item['formatted_created_date_month_year']][] = $item;
        }
        $temp = array('data' => $topicArray, 'total_count' => $totalCount);

        $hash = json_encode($temp);
        return $hash;
    }
}