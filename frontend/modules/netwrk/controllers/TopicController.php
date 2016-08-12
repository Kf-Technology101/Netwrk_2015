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
use frontend\modules\netwrk\controllers\ApiController;
use frontend\modules\netwrk\models\UserGroup;
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
        $posts = Post::find()->where('topic_id = 1')->andWhere('status != -1')->orderBy(['created_at'=> SORT_DESC])->all();
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
        if($_GET['group']){
            $group_id = $_GET['group'];
            $by_group = true;
        } else {
            $group_id = null;
            $by_group = false;
        }
        $topic_id = isset($_GET['topic_id']) ? $_GET['topic_id']: '';

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/topic/topic-page?city='.$city]);
        }

        $isCreateFromBlueDot = (isset($_GET['isCreateFromBlueDot'])  && $_GET['isCreateFromBlueDot'] == true) ? $_GET['isCreateFromBlueDot'] : false ;
        $data = [];
        if ($isCreateFromBlueDot == true) {
            $zip_code = $_GET['zipcode'];

            $cities = City::find()->where(['zip_code' => $zip_code])->all();
            foreach ($cities as $item) {
                $cityData = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'lat' => $item->lat,
                    'lng' => $item->lng,
                    'zip_code' => $item->zip_code,
                    'office' => isset($item->office)? $item->office : 'Community'
                ];
                array_push($data, $cityData);
            }
        }

        if(isset($city)) {
            $cty = City::findOne($city);
        }

        if($topic_id) {
            $topic = Topic::findOne($topic_id);
            $topic_object = [
              'topic' => $topic,
              'post' => json_decode($this->actionGetTopicById($topic->id))
            ];
        }

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

        return $this->render('mobile/create',[
            'city'=> $cty ,
            'city_id' =>$city_id,
            'data'=> (object)$object,
            'group_id'=> $group_id ,
            'by_group' => $by_group,
            'zipcode_cities' => $data,
            'isCreateFromBlueDot' => $isCreateFromBlueDot,
            'topic' => (object)$topic_object
            ]
        );
    }

    public function actionNewTopic() {
        $currentUser = Yii::$app->user->id;
        $city = $_POST['city'];
        $topic = $_POST['topic'];
        $post = $_POST['post'];
        $message = $_POST['message'];
        $topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : null;
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : null;

        $current_date = date('Y-m-d H:i:s');

        if($topic_id) {
            $Topic = Topic::find()->where(['id' => $topic_id])->one();
        } else {
            $Topic = new Topic;
        }

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
            if (isset($_POST['isCreateFromBlueDot']) && $_POST['isCreateFromBlueDot'] == true) {
                $Topic->lat = $_POST['lat'];
                $Topic->lng = $_POST['lng'];
            }
        } else {
            $groupId = $_POST['group'];
            if (empty($groupId)) throw new Exception("Group is empty");
            $group = Group::find()->where(array("id" => $groupId))->andWhere('status != -1')->one();
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

        if($post_id) {
            $Post = Post::findOne($post_id);
        } else {
            $Post = new Post();
        }

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
            $topices = Topic::find()->where($where)->andWhere('status != -1')->orderBy(['created_at'=> SORT_DESC]);
            break;
            case 'post':
            $topices = Topic::find()->where($where)->andWhere('status != -1')->orderBy(['post_count'=> SORT_DESC]);
            break;
            case 'view':
            $topices = Topic::find()->where($where)->andWhere('status != -1')->orderBy(['view_count'=> SORT_DESC]);
            break;
        }

        $countQuery = clone $topices;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $topices = $topices->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        $currentUserId = (Yii::$app->user->id) ? Yii::$app->user->id : 0;
        $data = [];
        foreach ($topices as $key => $value) {
            //if group permission is private then check, does logged in user is member of group.
            //and logged in user is not owner of group then
            if($value->group->permission == Group::PERMISSION_PRIVATE && $value->user_id !== $currentUserId ) {
                $skipRecord = false;
                if($currentUserId == 0) {
                    $skipRecord = true;
                } else {
                    $member = UserGroup::find()->where(['user_group.group_id' => $value->group->id])
                        ->andWhere(['user_group.user_id' => $currentUserId])
                        ->one();
                    //if currentLogged in user not member of group then skip the current record and jump to next record in loop
                    if($member == null) {
                        $skipRecord = true;
                    }
                }
                if($skipRecord) {
                    continue;
                }
            }
            $post_count = 0;
            $posts = Post::find()->where('topic_id ='.$value->id. ' AND post_type = 1')->andWhere('status != -1')->with('feedbackStat')->orderBy(['created_at'=> SORT_DESC])->all();

            foreach ($posts as $key => $post){
                $post_points = $post->feedbackStat->points ? $post->feedbackStat->points : 0;
                if($post_points > Yii::$app->params['FeedbackHideObjectLimit']) {
                    if($key < 3){
                        array_push($data_post,'#'.$post->title);
                    }
                    $post_count++;
                }
            }
            $post_data = array(
                'data_post'=> $data_post,
            );

            $num_view = UtilitiesFunc::ChangeFormatNumber($value->view_count);
            $num_post = UtilitiesFunc::ChangeFormatNumber($post_count);
            $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
            $data_post = [];

            $topic = array(
                'id'=> $value->id,
                'city_id'=>$value->city_id,
                'city_name'=> $value->city->name,
                'title'=>$value->title,
                'post_count' => $post_count ? $post_count : 0,
                'post_count_format' => $num_post,
                'view_count'=> $num_view > 0 ? $num_view : 0,
                'img'=> Url::to('@web/img/icon/timehdpi.png'),
                'created_at'=>$num_date,
                'post'=> $post_data,
                'user_id' => $value->user_id,
                'owner' => ($currentUserId == $value->user_id ? true : false),
                'lat' => $value->lat,
                'lng' => $value->lng
                );
            array_push($data,$topic);
        }

        $temp = array('data' => $data, 'is_favorite' => $is_favorite);
        if (!empty($cty)) {
            $temp['city'] = ($cty ? $cty->zip_code : $zipcode);
            $temp['city_id'] = ($cty ? $cty->id : '');
            $temp['office_type'] = ($cty ? $cty->office_type : '');
        }

        $hash = json_encode($temp);
        return $hash;
    }

    public function actionTopicPage() {
        $city = $_GET['city'];
        $title = @Yii::$app->request->get('title');

        $object = array();

        $cty = City::findOne($city);
        //check does city is favorited by logged in user
        if (!empty($cty)) {
            $is_favorite = Favorite::isFavoritedByUser('city', $cty->id);
        } else {
            $is_favorite = false;
        }

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

        $temp = array('data' => (object)$object, 'is_favorite' => $is_favorite);
        if (!empty($cty)) {
            $temp['city'] = ($cty ? $cty->zip_code : $zip_code);
            $temp['city_id'] = ($cty ? $cty->id : '');
            $temp['office_type'] = ($cty ? $cty->office_type : '');
        }
        return $this->render('mobile/index', $temp);
    }

    public function actionUpdateViewTopic(){
        $id = $_POST['topic'];

        $topic = Topic::findOne($id);
        $topic->view_count ++;
        $topic->update();
    }

    public function actionGetTopic(){
        $id = $_POST['topic'];

        $topic = Topic::find()->where('id ='.$id)->andWhere('status != -1')->one();

        return json_encode([
            'title' => $topic->title,
            'zipcode' => $topic->city->zip_code,
            'office_type' => $topic->city->office_type
        ]);
    }
    public function actionGetTopicByCity(){
        $city_id = $_GET['city_id'];

        $topics = Topic::find()->where(['city_id' => $city_id])
            ->andWhere('status != -1')
            ->all();

        $data = [];
        if($topics) {
            foreach ($topics as $topic) {
                $topic = array(
                    "id" => $topic->id,
                    "city_id" => $topic->city_id,
                    "title" => $topic->title,
                    "user_id" => $topic->user_id
                );
                array_push($data, $topic);
            }
        }
        $hash = json_encode($data);
        return $hash;
    }

    public function actionGetTopicById($topic_id = null){
        $topic_id = $_GET['topic_id'];

        $query = new Query();
        //get topic with post details  by id
        $post = $query->select('post.*, topic.title as topic_title, city.id as city_id, city.zip_code, city.name as city_name')
            ->from('post')
            ->where(['topic.id' => $topic_id])
            ->innerJoin('topic', 'post.topic_id = topic.id')
            ->innerJoin('city', 'city.id = topic.city_id')
            ->orderBy('post.created_at ASC')
            ->limit(1)
            ->one();

        return json_encode([
            'topic_id'=>$post['topic_id'],
            'topic_title'=>$post['topic_title'],
            'city_id'=>$post['city_id'],
            'city_name'=>$post['city_name'],
            'zip_code'=>$post['zip_code'],
            'post_id'=>$post['id'],
            'post_title'=>$post['title'],
            'content'=>$post['content'],
        ]);
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

        $cty = City::findOne($city);
        $zipcode = $cty->zip_code;
        if($request){
            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            $office_type = $cty->office_type;

            $top_post = array(); //Post::GetTopPostUserJoinGlobal($limit, $city);
            $top_topic = array(); //Topic::GetTopTopicGlobal($limit, $city);
            $top_city = City::GetTopCityUserJoinGlobal($limit, $city);
            //$weather_feed[] = ApiController::actionGetZipWeatherData($zipcode);
            $weather_feed[] = (array)json_decode(ApiController::actionGetZipWeatherData($zipcode));
            $job_feed = (array)json_decode(ApiController::actionGetZipJobData($zipcode));

            $htf = new HistoryFeed();
            $query_feed = $htf->find()->where('city_id = '. $city)->orderBy(['created_at'=> SORT_DESC]);
            $countQuery = clone $query_feed;
            $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
            $data_feed = $query_feed->offset($pages->offset)
                                    ->limit($pages->limit)
                                    ->all();
            $feeds =[];
            foreach ($data_feed as $key => $value) {
                if($value->item->status != -1) {
                    if ($value->type_item == 'post') {
                        $feedback_stat = $value->item->feedbackStat->points ? $value->item->feedbackStat->points : 0;
                        if($feedback_stat > Yii::$app->params['FeedbackHideObjectLimit']) {
                            $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
                            $url_avatar = User::GetUrlAvatar($value->item->user->id, $value->item->user->profile->photo);

                            $item = [
                                'id' => $value->item->id,
                                'title' => $value->item->title,
                                'content' => $value->item->content,
                                'topic_id' => $value->item->topic_id,
                                'photo' => $url_avatar,
                                'city_id' => $value->item->topic->city_id,
                                'city_name' => $value->item->topic->city->name,
                                'created_at' => $value->created_at,
                                'appear_day' => $num_date,
                                'posted_by' => $value->item->user['profile']['first_name'] . " " . $value->item->user['profile']['last_name'],
                                'user_id' => $value->item->user_id,
                                'is_post' => 1
                            ];
                            array_push($feeds, $item);
                        }
                    } else {
                        $num_date = UtilitiesFunc::FormatDateTime($value->created_at);
                        $item = [
                            'id' => $value->item->id,
                            'title' => $value->item->title,
                            'city_id' => $value->item->city_id,
                            'city_name' => $value->item->city->name,
                            'created_at' => $value->created_at,
                            'appear_day' => $num_date,
                            'created_by' => $value->item->user['profile']['first_name'] . " " . $value->item->user['profile']['last_name'],
                            'is_post' => 0
                        ];
                        array_push($feeds, $item);
                    }
                }
            }

            $item = [
                'office_type' => $office_type,
                'top_post'=> $top_post,
                'top_topic'=> $top_topic,
                'feed' => $feeds,
                'weather_feed' => $weather_feed,
                'job_feed' => $job_feed
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
                $topices = Topic::find()->where($where)->andWhere('status != -1')->orderBy(['created_at'=> SORT_DESC]);
                break;
            default:
                $topices = Topic::find()->where($where)->andWhere('status != -1')->orderBy(['created_at'=> SORT_DESC]);
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

    /**
     * Get the topic by location, Fetch those topic which created from blue dot.
     * return topic data
     */
    public function actionGetTopicByLocation()
    {
        $swLat = $_POST['swLat'];
        $neLat = $_POST['neLat'];

        $swLng = $_POST['swLng'];
        $neLng = $_POST['neLng'];

        $geo_where = '(topic.lat >= '.$swLat.' AND topic.lat <= '.$neLat.' AND topic.lng >= '.$swLng.' AND topic.lng <= '.$neLng.')';

        $query = new Query();
        $data = $query->select('topic.*,
                city.id as city_id, city.zip_code, city.office, city.name, city.lat as city_lat, city.lng as city_lng'
        )->from('topic')
        ->join('INNER JOIN', 'city', 'city.id = topic.city_id')
        ->where($geo_where)
        ->andWhere(['not', ['topic.status'=> '-1']])
        ->andWhere(['not', ['topic.lat' => null]])
        ->andWhere(['not', ['topic.lng' => null]])
        ->orderBy(['topic.created_at'=> SORT_DESC]);

        $topics = $query->all();
        $data = [];
        foreach ($topics as $key => $value) {
            $topic = array(
                "id" => $value['id'],
                "city_id" => $value['city_id'],
                "city_name" => $value['name'],
                "office" => $value['office'],
                "zip_code" => $value['zip_code'],
                "user_id" => $value['user_id'],
                "title" => $value['title'],
                "lat" => $value['lat'],
                "lng" => $value['lng']
            );
            array_push($data, $topic);
        }

        $hash = json_encode($data);
        return $hash;
    }

    /*
     * @throws \yii\db\Exception
     */
    public function actionDelete(){
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $currentUserId = Yii::$app->user->id;
            $currentUser = User::find()->where(array("id" => $currentUserId))->one();
            $id = $_POST['id'];

            if (empty($currentUser)) {
                throw new Exception("Unknown error, please try to re-login");
            }

            if (empty($_POST['id'])) throw new Exception("Nothing to delete");

            $topic = Topic::findOne($id);

            if (empty($topic) || $topic->user_id != $currentUserId) {
                throw new Exception("Unknown post or user");
            }

            $topic->status = -1;
            $topic->save();

            // Find all posts from this topic and update those status
            $topic_posts = Post::find()->where('topic_id = '. $id)->andWhere('status != -1')->all();

            foreach ($topic_posts as $key => $value) {
                $post = Post::findOne($value->id);
                $post->status = -1;
                $post->save();
            }

            $transaction->commit();

            die(json_encode(array("error" => false)));
        } catch (Exception $e) {
            $transaction->rollBack();
            die(json_encode(array("error" => true, "message" => $e->getMessage())));
        }
    }
}