<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use yii\web\Session;
use yii\web\Cookie;
use yii\db\Query;
use yii\helpers\Url;
use gisconverter;
use gisconverter\WKT;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\Hashtag;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\Favorite;
use frontend\modules\netwrk\models\HistoryFeed;
use frontend\modules\netwrk\models\WsMessages;
use frontend\modules\netwrk\models\Temp;
use frontend\components\UtilitiesFunc;

class DefaultController extends BaseController
{

    public function actionIndex()
    {
        if(Yii::$app->getRequest()->getCookies()->has('isCoverPageVisited')){
            return $this->render($this->getIsMobile() ? 'mobile/index' : 'index');
        }else{
            return $this->render($this->getIsMobile() ? 'mobile/cover_page' : 'cover_page');
        }
    }

    public function actionSetCoverCookie(){
        $zip_code = ($_GET['post_code']) ? $_GET['post_code'] : 0;
        $lat = $_GET['places'][0]['latitude'];
        $lng = $_GET['places'][0]['longitude'];
        $city = $_GET['places'][0]['place name'];
        $state = $_GET['places'][0]['state'];
        $state_abbr = $_GET['places'][0]['state abbreviation'];

        $c = Yii::$app->response->cookies;

        $cookie = new Cookie(['name'=>'nw_zipCode', 'value'=> $zip_code, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'nw_city', 'value'=> $city, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'nw_lat', 'value'=> $lat, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'nw_lng', 'value'=> $lng, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'nw_state', 'value'=> $state, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'nw_stateAbbr', 'value'=> $state_abbr, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'isCoverPageVisited', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'isAccepted', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);
        $cookie = new Cookie(['name'=>'nw_selectedZip', 'value'=> $zip_code, 'expire'=> (time()+(365*86400))]);
        $c->add($cookie);

        $cookie = new Cookie(['name'=>'nw_selectedZip', 'value'=> $zip_code, 'expire'=> (time()+(365*86400))]);
        $cookie = new Cookie(['name' => 'nw_selectedLocation', 'value' => $city, 'expire' => (time() + (365 * 86400))]);
        $c->add($cookie);

        return true;
    }

    public function actionSetWelcomeCookie()
    {
        $c = Yii::$app->response->cookies;

        $cookie = new Cookie(['name' => 'nw_welcomePage', 'value' => 'false', 'expire' => (time() + (365 * 86400))]);
        $c->add($cookie);

        return true;
    }

    public function actionSetUserLocationInfoCookie()
    {
        $c = Yii::$app->response->cookies;

        $cookie = new Cookie(['name' => 'nw_userLocationInfo', 'value' => 'false', 'expire' => (time() + (365 * 86400))]);
        $c->add($cookie);

        return true;
    }

    public function actionGetUserProfile()
    {
        if (Yii::$app->user->id) {
            $user = Profile::GetCommunities();
        }

        if ($user['photo'] == null){
            $image = 'img/icon/no_avatar.jpg';
        }else{
            $image = 'uploads/'.$user['user_id'].'/'.$user['photo'];
        }

        $data = [
                'user_id'=> $user['user_id'],
                'name'=> $user['first_name']." ".$user['last_name'],
                'avatar'=> $image,
                'city_id'=>$user['city_id'],
                'created_date' => $user['create_time']
            ];
        $data = json_encode($data);
        return $data;
    }

    public function actionGetUserPosition()
    {

        $user = User::find()->where('id ='.Yii::$app->user->id)->with('profile')->one();
        $data =[
            'lat'=> $user->profile->lat,
            'lng'=> $user->profile->lng,
        ];

        $hash = json_encode($data);
        return $hash;
    }

    public function actionCheckExistZipcode()
    {
        $zipcode = $_POST['zipcode'];
        $city = City::find()->where(['zip_code'=>$zipcode])->one();

        if($city){
            $data = ['status'=> 1,'city'=>$city->id];
        }else{
            $data = ['status'=> 0];
        }
        $hash = json_encode($data);
        return $hash;
    }

    public function actionCheckExistPlaceZipcode()
    {
        $zipcode = $_POST['zipcode'];
        $place_name = $_POST['place_name'];
        $type = $_POST['type'];
        if($type == 'gov'){
            $city = City::find()->where(['zip_code'=>$zipcode, 'office_type'=>'government'])->one();
        }else{
            $city = City::find()->where(['zip_code'=>$zipcode, 'office_type'=>'university'])->one();
        }

        if($city){
            $data = ['status'=> 1,'city'=>$city->id];
        }else{
            $cty = City::find()->where(['zip_code'=>$zipcode])->one();
            $data = ['status'=> 0, 'city_name'=>$cty->name];
        }
        $hash = json_encode($data);
        return $hash;
    }

    // Get 4 topic have post most
    protected static function Top4Topices($city,$limit)
    {
        //$limit get size object
        $topices = Topic::GetTopTopic($city,$limit);
        $data =[];
        foreach ($topices as $key => $value){
            $item =[
                'id'=> $value->id,
                'name'=> $value->title,
                'city'=> $value->city->id,
                'city_name'=>$value->city->zip_code,
                'num_post'=> $value->post_count
            ];

            array_push($data, $item);
        }

        return $data;
    }

    //Get top 4 hashtag in City
    protected function Trending4Hashtag($city,$limit){
        $hashtags = Hashtag::TopHashtagInCity($city->id,$limit);
        $data =[];
        foreach ($hashtags as $hashtag){
            $item = [
                'hashtag_id'=> $hashtag['id'],
                'hashtag_name'=> $hashtag['hashtag'],
                'hashtag_post'=> $hashtag['count_hash'],
                'topic_id' => $hashtag['topic_id'],
                'topic_title' => $hashtag['topic_title']
            ];
            array_push($data, $item);
        }

        return $data;
    }

    //Get Similarpost and trending number on top 4 post
    protected function Trending4Post($city,$limit)
    {
        $hashtag = [];
        foreach ($city->topics as $topic){
            foreach ($topic->posts as $post) {
                # code...
                $arr = explode(' ',trim($post->title));
                $item = [
                    'post_id'=> $post->id,
                    'post_name'=> $arr[0],
                    'post_trending'=> Post::SearchHashTagPost($arr[0],$city->id),
                    'user_join'=>Post::CountUserJoinPost($post->id)
                ];
                array_push($hashtag, $item);
            }
        }
        $data = $this->GetTop4Trending($hashtag,$limit);

        return $data;
    }

    //Sort 4 post have trending most
    protected static function GetTop4Trending($hashtag,$limit)
    {
        $sortArray = [];

        foreach($hashtag as $person){
            foreach($person as $key=>$value){
                if(!isset($sortArray[$key])){
                    $sortArray[$key] = [];
                }
                $sortArray[$key][] = $value;
            }
        }

        $orderby = "user_join";
        array_multisort($sortArray[$orderby],SORT_DESC,$hashtag);

        return array_slice($hashtag, 0, $limit);
    }

    protected static function GetPostMostBrilliant($city)
    {
        $post = Post::GetPostMostBrilliant($city);
        $item = [
                    'post_id'=>$post->id,
                    'brilliant'=>$post->brilliant_count ? $post->brilliant_count : 0,
                    'name_post'=> $post->title,
                    'content' => $post->content,
                    'post_type'=> $post->post_type,
                    'topic_id' => $post->topic_id,
                    'user'=> $post->user
                ];

        return $item;
    }

    public function actionGetMakerDefaultZoom()
    {
        $city_ids = $this->actionGetCitiesFromCookie('id', 'social');
        $userId = isset(Yii::$app->user->id) ? Yii::$app->user->id : null;

        $favoriteCommunities = Yii::$app->runAction('netwrk/favorite/get-favorite-communities-by-user');
        $favoriteCommunities = json_decode($favoriteCommunities);
        $favoriteData = [];
        foreach ($favoriteCommunities->data as  $value) {
            array_push($favoriteData, $value->city_id);
        }

        if(sizeof($favoriteData) > 0) {
            $followed_city_ids = implode(',', $favoriteData);
            $city_ids = $city_ids.','.$followed_city_ids;
        }

        //get logged in users home location(Zip) city id.
        if(yii::$app->user->id) {
            $loggedInUser = User::find()->with('profile')->where(['id' => yii::$app->user->id])->one();
            $homeZipCode = $loggedInUser->profile->zip_code;
            if($homeZipCode) {
                $city = new City();
                $homeCityId = $city->find()->select('city.id')
                    ->where(['zip_code' => $homeZipCode])
                    ->andWhere('office_type is null')
                    ->orderBy('city.id asc')
                    ->limit(1)
                    ->one();
            }
            if($homeCityId) {
                $city_ids = $city_ids.','.$homeCityId->id;
            }
        }

        $maxlength = Yii::$app->params['MaxlengthContent'];
        $limitHover = Yii::$app->params['LimitObjectHoverPopup'];
        $query = new Query();
        $datas = $query->select('COUNT(DISTINCT ws_messages.user_id) AS count_user_comment, city.id, COUNT(post.id) AS post_count')
            ->from('city')
            ->leftJoin('topic', 'city.id=topic.city_id')
            ->leftJoin('post', 'topic.id=post.topic_id')
            ->leftJoin('ws_messages', 'post.id=ws_messages.post_id')
            ->where('city.id IN ('.$city_ids.')')
            ->groupBy('city.id')
            ->orderBy('count_user_comment DESC, post_count DESC')
            ->all();
        $zipcodes = array();
        for ($i=0; $i < count($datas); $i++) {
            array_push($zipcodes, $datas[$i]['id']);
        }
        // $cities = City::find()->with('topics.posts')->orderBy(['user_count'=> SORT_DESC,'post_count'=> SORT_DESC])->limit(10)->all();
        $cities = City::find()->with('topics.posts')->where(['id' => $zipcodes])->all();
        // echo '<pre>';var_dump($cities);die;

        $data = [];
        //$img = '/img/icon/map_icon_community_v_2.png';
        // SELECT COUNT(DISTINCT a.user_id) AS count_user_comment FROM `ws_messages` AS a WHERE post_id = 247;
        // or
        // SELECT COUNT(DISTINCT a.user_id) AS count_user_comment, c.post_id  FROM `ws_messages` as a, post as b, topic as
        // c, city as d WHERE a.post_id=b.id AND b.topic_id=c.id AND c.city_id=d.id GROUP BY a.post_id ORDER BY count_user_comment
        //  DESC LIMIT 10;

        foreach ($cities as $key => $value) {
            /*if($value->office_type == 'university'){
                $img = '/img/icon/map_icon_university_v_2.png';
            } else if($value->office_type == 'government'){
                $img = '/img/icon/map_icon_government_v_2.png';
            } else {
                $img = '/img/icon/map_icon_community_v_2.png';
            }*/

            if(isset($value->topics[0])) {
                $post = $this->GetPostMostBrilliant($value->id);
                $user_post = '';//$post['user'];
                $content = '';//$post['content'];
                $topices = $this->Top4Topices($value->id,$limitHover);
                // $trending = $this->Trending4Post($value,$limitHover);
                $trending_hashtag = $this->Trending4Hashtag($value,$limitHover);
                
                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> ($value->office != '') ? $value->office : $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic'=> $topices,
                    // 'trending_post'=> $trending,
                    'trending_hashtag'=> $trending_hashtag,
                    //'mapicon'=>$img,
                    'user'=>[
                        'username'  => $user_post->profile->first_name." ".$user_post->profile->last_name,
                        'avatar'    => $user_post->profile->photo ? Url::to('@web/uploads/'.$user_post->id.'/'.$user_post->profile->photo) : Url::to('@web/img/icon/no_avatar.jpg'),
                        'work'      => $user_post->profile->work,
                        'zipcode'   => $user_post->profile->zip_code,
                        'place'     => $user_post->profile->city ? $user_post->profile->city->name : ''
                    ],
                    'post'=>$post
                );
                array_push($data,$netwrk);
            } else {
                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic' => '',
                    //'mapicon'=>$img,
                    'post'=> array(
                        'post_id'=>-1,
                        'name_post'=> '',
                        'content' => '',
                        'topic_id' => '',
                    )
                );

                array_push($data,$netwrk);
            }
        }

        $hash = json_encode($data);
        return $hash;
    }

    public function actionGetMakerMaxZoom()
    {
        $swLat = $_POST['swLat'];
        $neLat = $_POST['neLat'];

        $swLng = $_POST['swLng'];
        $neLng = $_POST['neLng'];

        $geo_where = '(lat >= '.$swLat.' AND lat <= '.$neLat.' AND lng >= '.$swLng.' AND lng <= '.$neLng.')';

        $maxlength = Yii::$app->params['MaxlengthContent'];
        $limitHover = Yii::$app->params['LimitObjectHoverPopup'];

        $cities = City::find()
            ->where($geo_where)
            ->with('topics.posts')
            ->orderBy(['post_count'=> SORT_DESC])
            ->all();

        $data = [];
        //$img = '/img/icon/map_icon_community_v_2.png';

        foreach ($cities as $key => $value) {
            /*if($value->office_type == 'university'){
                $img = '/img/icon/map_icon_university_v_2.png';
            } else if($value->office_type == 'government'){
                $img = '/img/icon/map_icon_government_v_2.png';
            } else {
                $img = '/img/icon/map_icon_community_v_2.png';
            }*/

            if(isset($value->topics[0])) {
                $post = $this->GetPostMostBrilliant($value->id);
                $user_post = '';//$post['user'];
                $content = '';//$post['content'];
                $topices = $this->Top4Topices($value->id,$limitHover);
                // $trending = $this->Trending4Post($value);
                $trending_hashtag = $this->Trending4Hashtag($value,$limitHover);

                // if(strlen($content) > $maxlength ){
                //     $content = substr($post->content,0,$maxlength ) ;
                //     $content = $content."...";
                // }

                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic'=> $topices,
                    'trending_hashtag'=> $trending_hashtag,
                    //'mapicon'=>$img,
                    'user'=>[
                        'username'  => $user_post->profile->first_name." ".$user_post->profile->last_name,
                        'avatar'    => $user_post->profile->photo ? Url::to('@web/uploads/'.$user_post->id.'/'.$user_post->profile->photo) : Url::to('@web/img/icon/no_avatar.jpg'),
                        'work'      => $user_post->profile->work,
                        'zipcode'   => $user_post->profile->zip_code,
                        'place'     => $user_post->profile->city ? $user_post->profile->city->name : ''
                    ],
                    'post'=>$post
                );
                array_push($data,$netwrk);
            } else {
                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic' => '',
                    //'mapicon'=>$img,
                    'post'=> array(
                        'post_id'=>-1,
                        'name_post'=> '',
                        'content' => '',
                        'topic_id' => '',
                    )
                );

                array_push($data,$netwrk);
            }
        }

        $hash = json_encode($data);
        return $hash;
    }

    public function actionGetMarkerInfo()
    {
        $city_id = $_POST['city_id'];

        $maxlength = Yii::$app->params['MaxlengthContent'];
        $limitHover = Yii::$app->params['LimitObjectHoverPopup'];

        $cities = City::find()->with('topics.posts')->where(['id' => $city_id])->all();

        $data = [];

        foreach ($cities as $key => $value) {
            if(isset($value->topics[0])) {
                $post = $this->GetPostMostBrilliant($value->id);
                $user_post = $post['user'];
                $content = $post['content'];
                $topices = $this->Top4Topices($value->id,$limitHover);
                // $trending = $this->Trending4Post($value);
                $trending_hashtag = $this->Trending4Hashtag($value,$limitHover);

                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic'=> $topices,
                    'trending_hashtag'=> $trending_hashtag,
                    'user'=>[
                        'username'  => $user_post->profile->first_name." ".$user_post->profile->last_name,
                        'avatar'    => $user_post->profile->photo ? Url::to('@web/uploads/'.$user_post->id.'/'.$user_post->profile->photo) : Url::to('@web/img/icon/no_avatar.jpg'),
                        'work'      => $user_post->profile->work,
                        'zipcode'   => $user_post->profile->zip_code,
                        'place'     => $user_post->profile->city ? $user_post->profile->city->name : ''
                    ],
                    'post'=>$post
                );
                array_push($data,$netwrk);
            } else {
                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic' => '',
                    'post'=> array(
                        'post_id'=>-1,
                        'name_post'=> '',
                        'content' => '',
                        'topic_id' => '',
                    )
                );
                array_push($data,$netwrk);
            }
        }

        $hash = json_encode($data);
        return $hash;
    }

    public function actionGetMarkerUpdate()
    {
        $maxlength = Yii::$app->params['MaxlengthContent'];
        $limitHover = Yii::$app->params['LimitObjectHoverPopup'];
        $city_id = $_POST['city'];
        $city= City::find()->with('topics.posts')->where(['id'=>$city_id])->one();

        $data = [];
        //$img = '/img/icon/map_icon_community_v_2.png';

        if($city){
            if(isset($city->topics[0])) {
                $post = $this->GetPostMostBrilliant($city->id);
                $user_post = $post['user'];
                $content = $post['content'];
                $topices = $this->Top4Topices($city->id, $limitHover);
                // $trending = $this->Trending4Post($city);
                $trending_hashtag = $this->Trending4Hashtag($city,$limitHover);

                // if(strlen($content) > $maxlength ){
                //     $content = substr($post->content,0,$maxlength ) ;
                //     $content = $content."...";
                // }
                /*if($city->office_type == 'university'){
                    $img = './img/icon/map_icon_university_v_2.png';
                } else if($city->office_type == 'government'){
                    $img = './img/icon/map_icon_government_v_2.png';
                } else {
                    $img = '/img/icon/map_icon_community_v_2.png';
                }*/

                $netwrk = array(
                    'id'=> $city->id,
                    'name'=> $city->name,
                    'lat'=> $city->lat,
                    'lng'=>$city->lng,
                    'zip_code'=> $city->zip_code,
                    'office'=>$city->office,
                    'office_type'=>$city->office_type,
                    'topic'=> $topices,
                    // 'trending_post'=> $trending,
                    'trending_hashtag'=> $trending_hashtag,
                    //'mapicon'=>$img,
                    'user'=>[
                        'username'  => $user_post->profile->first_name." ".$user_post->profile->last_name,
                        'avatar'    => $user_post->profile->photo ? Url::to('@web/uploads/'.$user_post->id.'/'.$user_post->profile->photo) : Url::to('@web/img/icon/no_avatar.jpg'),
                        'work'      => $user_post->profile->work,
                        'zipcode'   => $user_post->profile->zip_code,
                        'place'     => $user_post->profile->city ? $user_post->profile->city->name : ''
                    ],
                    'post'=>$post
                );
            } else {
                $netwrk = array(
                    'id'=> $city->id,
                    'name'=> $city->name,
                    'lat'=> $city->lat,
                    'lng'=>$city->lng,
                    'zip_code'=> $city->zip_code,
                    'office'=>$city->office,
                    'office_type'=>$city->office_type,
                    'topic' => '',
                    //'mapicon'=>$img,
                    'post'=> array(
                        'post_id'=>-1,
                        'name_post'=> '',
                        'content' => '',
                        'topic_id' => '',
                    )
                );
            }
        }

        $hash = json_encode($netwrk);
        return $hash;
    }

    /**
     * GET Help location, currently using static location, need to be updated on database later
     *
     *  
     * 
     * @return Array Maker
     */
    public function actionGetMarkerHelp()
    {
        $maxlength = Yii::$app->params['MaxlengthContent'];
        // hard code for `Fishers` city
        $city= City::find()->with('topics.posts')->where(['office'=> 'Ritchey Woods Nature Preserve'])->one();

        $data = [];
        //$img = '/img/icon/map_icon_community_v_2.png';

        $netwrk = array(
                'id'=> $city->id,
                'name'=> $city->name,
                'lat'=> $city->lat,
                'lng'=> $city->lng,
                'zip_code'=> $city->zip_code,
                'office'=>$city->office,
                'office_type'=>$city->office_type,
                'topic' => '',
                //'mapicon'=>$img,
                'post'=> array(
                    'post_id'=>-1,
                    'name_post'=> '',
                    'content' => '',
                    'topic_id' => '',
                )
            );

        $hash = json_encode($netwrk);
        return $hash;
    }

    public function actionPlaceSave(){
        $zipcode = $_POST['zip_code'];
        $city_name = $_POST['netwrk_name'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $office = $_POST['office'];
        $office_type = $_POST['office_type'];
        $state = $_POST['state'];
        $stateAbbr = $_POST['stateAbbr'];
        $county = $_POST['county'];

        $netwrk = new City;
        $netwrk->name = $city_name;
        $netwrk->lat = $lat;
        $netwrk->lng = $lng;
        $netwrk->zip_code = $zipcode;
        $netwrk->office = $office;
        $netwrk->office_type = $office_type;
        $netwrk->state = $state;
        $netwrk->state_abbreviation = $stateAbbr;
        $netwrk->county = $county;

        $netwrk->save();
        return json_encode($netwrk->id);
    }

    public function actionInsertLocalUniversity(){
        $zcodes = City::find()->select(['id', 'zip_code'])->where(['office_type'=>'university'])->all();
        $datas = City::find()->select(['id', 'zip_code'])->where('office is null')->all();
        $arrs = [];
        for ($i=0; $i < count($zcodes); $i++) {
            array_push($arrs, $zcodes[$i]->zip_code);
        }

        $arrs2 = [];
        for ($i=0; $i < count($datas); $i++) {
            array_push($arrs2, $datas[$i]->zip_code);
        }

        for ($i=0; $i < count($arrs); $i++) {
            if(($key = array_search($arrs[$i], $arrs2)) !== false){
                unset($arrs2[$key]);
            }
        }

        $ctys = City::find()->where(['zip_code'=>$arrs2])->andWhere('office_type is null')->all();

        for ($i=0; $i < count($ctys); $i++) {
            $lat = $ctys[$i]->lat + 0.01;
            $lng = $ctys[$i]->lng + 0.01;

            $temp = Temp::find()->where(['zipcode' => $ctys[$i]->zip_code])->one();

            if($lat >= $temp->lat_max)
                $lat = $temp->lat_max - 0.005;
            if($lng >= $temp->lng_max)
                $lng = $temp->lng_max - 0.005;

            $city = new City();
            $city->name = $ctys[$i]->name;
            $city->lat = $lat;
            $city->lng = $lng;
            $city->zip_code = $ctys[$i]->zip_code;
            $city->office = 'Local University';
            $city->office_type = 'university';
            $city->save();
        }
    }

    public function actionInsertLocalGovernment(){
        $zcodes = City::find()->select(['id', 'zip_code'])->where(['office_type'=>'government'])->all();
        $datas = City::find()->select(['id', 'zip_code'])->where('office is null')->all();
        $arrs = [];
        for ($i=0; $i < count($zcodes); $i++) {
            array_push($arrs, $zcodes[$i]->zip_code);
        }

        $arrs2 = [];
        for ($i=0; $i < count($datas); $i++) {
            array_push($arrs2, $datas[$i]->zip_code);
        }

        for ($i=0; $i < count($arrs); $i++) {
            if(($key = array_search($arrs[$i], $arrs2)) !== false){
                unset($arrs2[$key]);
            }
        }

        $ctys = City::find()->where(['zip_code'=>$arrs2])->andWhere('office_type is null')->all();

        for ($i=0; $i < count($ctys); $i++) {
            $lat = $ctys[$i]->lat - 0.01;
            $lng = $ctys[$i]->lng + 0.01;

            $temp = Temp::find()->where(['zipcode' => $ctys[$i]->zip_code])->one();

            if($lat <= $temp->lat_min)
                $lat = $temp->lat_min + 0.005;
            if($lng >= $temp->lng_max)
                $lng = $temp->lng_max - 0.005;
            $city = new City();
            $city->name = $ctys[$i]->name;
            $city->lat = $lat;
            $city->lng = $lng;
            $city->zip_code = $ctys[$i]->zip_code;
            $city->office = 'Local Government Office';
            $city->office_type = 'government';
            $city->save();
        }
    }

    public function actionFeedGlobal(){
        $result_data = $_POST['result-data'];
        $request = Yii::$app->request->isAjax;
        $cookies = Yii::$app->request->cookies;
        $item = array();

        if($request){
            $limit = Yii::$app->params['LimitObjectFeedGlobal'];
            if($result_data == 'all') {
                $party_lines = array();

                $city_ids = $this->actionGetCitiesFromCookie('id');
                $hq_city_id = $this->actionGetHQZipCityFromCookie();

                $hq_post = Post::GetHQPostGlobal($hq_city_id);
                $top_post = Post::GetTopPostUserJoinGlobal($limit,null,null);
                $top_topic = Topic::GetTopTopicGlobal($limit, null,$city_ids);
                //$top_city = City::GetTopCityUserJoinGlobal($limit,$city_ids);
                //$top_communities = City::TopHashTag_City($top_city,$limit);
                $front_cities = City::GetCities($limit,$city_ids);
                $top_communities = City::TopHashTag_City($front_cities,$limit);

                // If user is logged in then get his followed communities feeds
                if(Yii::$app->user->id) {
                    $feeds = json_decode($this->actionGetFeedByUser(), true);
                }
                // else get the feeds for the communities from zip or city entered on cover page
                else {
                    $feeds = json_decode($this->actionGetFeedByCities($city_ids), true);
                }

                $item = [
                    'hq_post' => $hq_post,
                    'top_post' => $top_post,
                    'top_topic' => $top_topic,
                    'top_communities' => $top_communities,
                    'feeds' => $feeds
                ];
            } elseif($result_data == 'feed-and-hq-post') {
                $city_ids = $this->actionGetCitiesFromCookie('id');
                $hq_city_id = $this->actionGetHQZipCityFromCookie();

                $hq_post = Post::GetHQPostGlobal($hq_city_id);

                // If user is logged in then get his followed communities feeds
                if(Yii::$app->user->id) {
                    $feeds = json_decode($this->actionGetFeedByUser(), true);
                }
                // else get the feeds for the communities from zip or city entered on cover page
                else {
                    $feeds = json_decode($this->actionGetFeedByCities($city_ids), true);
                }

                $item = [
                    'hq_post' => $hq_post,
                    'feeds' => $feeds
                ];
            }

            $hash = json_encode($item);
            return $hash;
        }
    }

    /**
     * set selected zipcode cookie
     * @return bool
     */
    public function actionSetSelectedZipCodeCookie()
    {
        $c = Yii::$app->response->cookies;

        $zip_code = $_GET['zip_code'];
        $city = $_GET['city'];


        $cookie = new Cookie(['name' => 'nw_selectedZip', 'value' => $zip_code, 'expire' => (time() + (365 * 86400))]);
        $c->add($cookie);

        if(!$city) {
            $city = City::find()
                ->where('zip_code = '.$zip_code)
                ->andWhere('office_type is null')
                ->one();

            $city = $city->name;
        }

        if($city) {
            $cookie = new Cookie(['name' => 'nw_selectedLocation', 'value' => $city, 'expire' => (time() + (365 * 86400))]);
            $c->add($cookie);
        }

        $item = [
            'city' => $city
        ];

        $hash = json_encode($item);
        return $hash;
    }

    /**
     * get feeds by zipcode, fetch feeds of zipcode area
     * @return string
     *
     */
    public function actionGetFeedsBySelectedZipCode(){
        $request = Yii::$app->request->isAjax;

        $cookies = Yii::$app->request->cookies;
        //if selectedZip not set then use cover page zip to fetch feeds
        $zip_code = ($cookies->getValue('nw_selectedZip')) ? $cookies->getValue('nw_selectedZip') : $cookies->getValue('nw_zipCode');

        if($request && $zip_code){
            $limit = Yii::$app->params['LimitObjectFeedGlobal'];
            $party_lines = array();

            $city = new City();
            $cities = $city->find()->select('city.*')
                ->where(['zip_code' => $zip_code])
                ->all();

            $city_ids = [];
            foreach ($cities as $city) {
                array_push($city_ids, $city->id);
            }

            //Get the feeds from zipcode cities
            $feeds = json_decode($this->actionGetFeedByCities($city_ids), true);

            $item = [
                'feeds' => $feeds,
                'selected_zipcode' => $zip_code
            ];

            $hash = json_encode($item);
            return $hash;
        }
    }

    public function actionLandingPage()
    {
        return $this->render($this->getIsMobile() ? 'mobile/landing_page' : '');
    }

    public function actionGetGroupsLoc() {
        $maxlength = Yii::$app->params['MaxlengthContent'];

        $groupId = isset($_GET['groupId']) ? $_GET['groupId'] : '';

        $swLat = $_GET['swLat'];
        $neLat = $_GET['neLat'];

        $swLng = $_GET['swLng'];
        $neLng = $_GET['neLng'];

        if($groupId) {
            $where = "group.latitude is not null and group.longitude is not null " . (!is_null($groupId) ? " and group.id = " . $groupId : "");
        } else {
            $where = '(group.latitude >= '.$swLat.' AND group.latitude <= '.$neLat.' AND group.longitude >= '.$swLng.' AND group.longitude <= '.$neLng.')';
        }
        $query = new Query();
        //selecting all coordinates with related
        $datas = $query->select('COUNT(DISTINCT ws_messages.user_id) AS count_user_comment, group.id, group.name, group.latitude, group.longitude, COUNT(post.id) AS post_count')
            ->from('group')
            ->where($where)
            //todo: make normally
            //->where("group.latitude is not null and group.longitude is not null " . (!is_null($groupId) ? " and group.id = " . $groupId : ""))
            ->leftJoin('topic', 'group.id=topic.group_id')
            ->leftJoin('post', 'topic.id=post.topic_id')
            ->leftJoin('ws_messages', 'post.id=ws_messages.post_id')
            ->groupBy('group.id')
            ->orderBy('count_user_comment DESC, post_count DESC')
            ->limit(10)
            ->all();
        $zipcodes = array();
        for ($i = 0; $i < count($datas); $i++) {
            array_push($zipcodes, $datas[$i]['id']);
        }
        // $cities = City::find()->with('topics.posts')->orderBy(['user_count'=> SORT_DESC,'post_count'=> SORT_DESC])->limit(10)->all();
        //$cities = City::find()->with('topics.posts')->where(['id' => $zipcodes])->all();

        $data = [];

        foreach ($datas as $key => $value) {
            $netwrk = array(
                "id" => $value['id'],
                "name" => $value['name'],
                "lat" => $value['latitude'],
                "lng" => $value['longitude'],
                'post' => array(
                    'post_id' => -1,
                    'name_post' => '',
                    'content' => '',
                    'topic_id' => '',
                )
            );
            array_push($data, $netwrk);
        }

        $hash = json_encode($data);
        return $hash;
    }

    public function actionHome()
    {
        return $this->render($this->getIsMobile() ? 'mobile/index' : 'index');
    }

    /**
     * [Function is used to get  List out users ALL created Topic/ Post within that netwrk, 3 posts within that netwrk and have most number of users joining the post discussion, 3 topics which have most posts within this zip code]
     * @param  $zipcode
     * @return array      [data of json]
     */
    public function actionGetFeedByUser() {
        $userId = isset($_GET['userId']) ? $_GET['userId'] : Yii::$app->user->id;
        $request = 1;//Yii::$app->request->isAjax;

        if($request){

            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            //fetch users favorited cities
            //todo: pagination on favorite city
            $favorite = new Favorite();
            $favoriteCities = $favorite->find()
                ->where([
                    'type' => 'city',
                    'user_id' => $userId,
                    'status' => 1
                ])->all();

            $cities = [];
            foreach ($favoriteCities as $city) {
                array_push($cities, $city->city_id);
            }

            //fetch history feed of users favorite cities
            $htf = new HistoryFeed();
            $history_feed = $htf->find()->select('history_feed.*, city.zip_code')
                ->join('INNER JOIN', 'city', 'city.id = history_feed.city_id')
                ->where(['city_id' => $cities])
                ->orderBy(['created_at'=> SORT_DESC]);

            //todo: pagination on history feed
            $data_feed = $history_feed->all();

            $feeds =[];
            foreach ($data_feed as $key => $value) {
                if($value->item->status != -1) {
                    if ($value->type_item == 'post') {
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
                    }
                    $feeds[$value->city_id][] = $item;
                }
            }
            $hash = json_encode($feeds);
            return $hash;
        }
    }

    public function actionGetCityById() {
        $data = [];
        $cityId = $_GET['city_id'];
        if ($cityId) {
            $city = new City();
            $item = $city->find()->select('city.*')
                ->where(['id' => $cityId])
                ->one();
            $data = [
                'id' => $item->id,
                'name' => $item->name,
                'lat' => $item->lat,
                'lng' => $item->lng,
                'zip_code' => $item->zip_code
            ];
        }
        $hash = json_encode($data);
        return $hash;
    }

    /**
     * Get city by zipcode, can be filter by office type
     * @return string
     */
    public function actionGetCityByZipcode() {
        $data = [];
        $zipCode = isset($_GET['zip_code']) ? $_GET['zip_code'] : '';
        $office_type = isset($_GET['office_type']) ? strtolower($_GET['office_type']) : '';
        $city = new City();
        if($zipCode) {
            $cities = $city->find()->select('city.*')
                ->where(['zip_code' => $zipCode]);

            //office_type is null means, office type is social
            switch ($office_type) {
                case 'social':
                    $cities->andWhere('office_type is null');
                    break;
                case 'university':
                    $cities->andWhere(['office_type' => 'university']);
                    break;
                case 'government':
                    $cities->andWhere(['office_type' => 'government']);
                    break;
                default:
                    break;
            }

            $cities =  $cities->all();
            foreach ($cities as $city) {
                $item = [
                    'id' => $city->id,
                    'name' => $city->name,
                    'lat' => $city->lat,
                    'lng' => $city->lng,
                    'zip_code' => $city->zip_code,
                    'office' => isset($city->office)? $city->office : 'Social',
                    'community' => isset($city->office)? $city->office : 'Community'
                ];
                array_push($data, $item);
            }
        }
        $hash = json_encode($data);
        return $hash;
    }

    public function actionGetZipBoundaries()
    {
        $return = [];

        // all zip codes from cookie
        $zip_codes_data = $this->actionGetCitiesFromCookie('zip');
        $zip_codes_data = explode(',',$zip_codes_data);

        $favoriteCommunities = Yii::$app->runAction('netwrk/favorite/get-favorite-communities-by-user');
        $favoriteCommunities = json_decode($favoriteCommunities);

        //get logged in users home location zipcode
        $loggedInUser = User::find()->with('profile')->where(['id' => yii::$app->user->id])->one();
        $homeZipCode = $loggedInUser->profile->zip_code;

        $homeZipData = [];
        if ($homeZipCode) {
            array_push($homeZipData, $homeZipCode);
        }

        $favoriteZipData = [];
        foreach ($favoriteCommunities->data as  $value) {
            array_push($favoriteZipData, $value->city_zipcode);
        }

        $allZipcodes = array_unique(array_merge($zip_codes_data, $favoriteZipData, $homeZipData));
        $allZipcodes = implode(',', $allZipcodes);
        // Array of zip codes
        $zip_array = explode(',',$allZipcodes);

        // Split the array into 15 zip codes array
        $zip_split_array = array_chunk($zip_array, 15);

        // Get boundaries data for each set of 15 zip codes
        foreach ($zip_split_array as $zip_split) {
            $zip_codes = implode(',',$zip_split);

            $returnData = $this->actionGetBoundariesFromData($zip_codes,'selected');

            // If features section is not null then only add to return array
            if(property_exists($returnData, 'features')) {
                if(sizeof($returnData->features) != 0)
                    array_push($return, $returnData);
            } else {
                $data = $this->actionGetZipBoundariesFromCurl($zip_codes);
                $returnData = $this->actionFormatBoundariesData($data,'selected');

                if(property_exists($returnData, 'features')) {
                    if(sizeof($returnData->features) != 0)
                        array_push($return, $returnData);
                }
            }

            if(property_exists($returnData, 'remainZip')) {
                $result = json_decode(json_encode($returnData), true);
                //$result = json_decode($returnData, true);
                $remaining_zip = implode(',',$result['remainZip']);

                $data = $this->actionGetZipBoundariesFromCurl($remaining_zip);
                $returnData = $this->actionFormatBoundariesData($data,'selected');

                if(property_exists($returnData, 'features')) {
                    if(sizeof($returnData->features) != 0)
                        array_push($return, $returnData);
                }
            }
        }

        die(json_encode($return));
    }

    public function actionGetVisibleZipBoundaries(){
        $swLat = $_GET['swLat'];
        $neLat = $_GET['neLat'];

        $swLng = $_GET['swLng'];
        $neLng = $_GET['neLng'];

        $cities_array = array();
        $return = [];

        $geo_where = '(lat >= '.$swLat.' AND lat <= '.$neLat.' AND lng >= '.$swLng.' AND lng <= '.$neLng.')';

        $cities = City::find()
            ->where($geo_where)
            ->all();

        // all zip codes from cookie
        $zip_codes_cookie = $this->actionGetCitiesFromCookie('zip');

        // Array of zip codes from cookie
        $zip_cookie_array = explode(',',$zip_codes_cookie);
        $favoriteCommunities = Yii::$app->runAction('netwrk/favorite/get-favorite-communities-by-user');
        $favoriteCommunities = json_decode($favoriteCommunities);
        $favoriteData = [];

        foreach ($favoriteCommunities->data as  $value) {
            array_push($favoriteData, $value->city_zipcode);
        }

        $allZipcodes = array_unique(array_merge($zip_cookie_array, $favoriteData));

        foreach ($cities as $key => $value) {
            if(!in_array($value->zip_code, $cities_array) && !in_array($value->zip_code, $allZipcodes)) {
                array_push($cities_array, $value->zip_code);
            }
        }

        if(sizeof($cities_array) == 0) {
            die(json_encode($return));
        }

        // all zip codes from visible area
        $zip_codes_data = implode(',',$cities_array);

        // Array of zip codes
        $zip_array = explode(',',$zip_codes_data);

        // Split the array into 15 zip codes array
        $zip_split_array = array_chunk($zip_array, 15);

        // Get boundaries data for each set of 15 zip codes
        foreach ($zip_split_array as $zip_split) {
            $zip_codes = implode(',',$zip_split);

            $returnData = $this->actionGetBoundariesFromData($zip_codes,'visible');

            // If features section is not null then only add to return array
            if(property_exists($returnData, 'features')) {
                if(sizeof($returnData->features) != 0)
                    array_push($return, $returnData);
            } else {
                $data = $this->actionGetZipBoundariesFromCurl($zip_codes);
                $returnData = $this->actionFormatBoundariesData($data,'visible');

                if(property_exists($returnData, 'features')) {
                    if(sizeof($returnData->features) != 0)
                        array_push($return, $returnData);
                }
            }

            if(property_exists($returnData, 'remainZip')) {
                $result = json_decode(json_encode($returnData), true);
                //$result = json_decode($returnData, true);
                $remaining_zip = implode(',', $result['remainZip']);

                $data = $this->actionGetZipBoundariesFromCurl($remaining_zip);
                $returnData = $this->actionFormatBoundariesData($data, 'selected');

                if (property_exists($returnData, 'features')) {
                    if (sizeof($returnData->features) != 0)
                        array_push($return, $returnData);
                }
            }
        }

        die(json_encode($return));
    }

    public function actionGetSingleZipBoundaries($zip_code)
    {
        $return = [];

        $returnData = $this->actionGetBoundariesFromData($zip_code,'blue-dot');

        // If features section is not null then only add to return array
        if(property_exists($returnData, 'features')) {
            if(sizeof($returnData->features) != 0)
                array_push($return, $returnData);
        } else {
            $data = $this->actionGetZipBoundariesFromCurl($zip_code);
            $returnData = $this->actionFormatBoundariesData($data,'blue-dot');

            if(property_exists($returnData, 'features')) {
                if(sizeof($returnData->features) != 0)
                    array_push($return, $returnData);
            }
        }

        die(json_encode($return));
    }

    public function actionFormatBoundariesData($data, $type){
        $cookies = Yii::$app->request->cookies;

        $city = ($cookies->getValue('nw_city')) ? $cookies->getValue('nw_city') : '';
        $state = ($cookies->getValue('nw_state')) ? $cookies->getValue('nw_state') : 'Indiana';

        $returnData = new \stdClass();

        $userId = (Yii::$app->user->id) ? Yii::$app->user->id : 0;

        //Adjusted object params according to api output
        $returnData->type = "FeatureCollection";
        foreach ($data as $key => $value) {
            // Get city details
            $query = new Query();

            $city = $query ->select('c.*, f.status')
                ->from('city c')
                ->leftJoin('favorite f', '(f.user_id = '.$userId.' AND f.city_id = c.id AND f.type = "city")')
                ->where(['c.zip_code' => $data[$key]->properties->ZCTA5CE10])
                ->andwhere(['c.office_type' => null])
                ->one();

            $zip_type = ($city['status'] == '1') ? 'Followed' : $type;

            $returnData->features[$key] = array(
                'type' => 'Feature',
                'properties' => (object)array(
                    'id' => $city['id'],
                    'zipCode' => $data[$key]->properties->ZCTA5CE10,
                    'city' => $city['name'],
                    'state' => $city['state'],
                    'lat' => $city['lat'],
                    'lng' => $city['lng'],
                    'type' => $zip_type
                ),
                'geometry' => (object)array(
                    'type' => $data[$key]->geometry->type,
                    'coordinates' => $data[$key]->geometry->coordinates
                )
            );
        }

        return $returnData;
    }

    public function actionGetZipBoundariesFromCurl($zip_code){
        $url = "http://boundaries.io/geographies/postal-codes?search=" . urlencode($zip_code);

        $headers[] = 'Accept: application/json';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';

        //Initiate curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);

        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);

        return (array)(json_decode($result));
    }

    public function actionGetFeedByCities($cities = array()) {
        $request = 1;//Yii::$app->request->isAjax;

        if($request){

            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            //fetch history feed of users favorite cities
            $htf = new HistoryFeed();
            $history_feed = $htf->find()->select('history_feed.*, city.zip_code')
                ->join('INNER JOIN', 'city', 'city.id = history_feed.city_id')
                ->where(['in','city_id',$cities])
                ->orderBy(['created_at'=> SORT_DESC]);

            //todo: pagination on history feed
            $data_feed = $history_feed->all();

            $feeds =[];
            foreach ($data_feed as $key => $value) {
                if($value->item->status != -1) {
                    if ($value->type_item == 'post') {
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
                    }
                    $feeds[$value->city_id][] = $item;
                }
            }

            $hash = json_encode($feeds);
            return $hash;
        }
    }

    public function actionGetCitiesFromCookie($output = 'id', $type=null){
        $cookies = Yii::$app->request->cookies;

        $zip_code = ($cookies->getValue('nw_zipCode')) ? $cookies->getValue('nw_zipCode') : 0;
        $city = ($cookies->getValue('nw_city')) ? $cookies->getValue('nw_city') : '';
        $state = ($cookies->getValue('nw_state')) ? $cookies->getValue('nw_state') : 'Indiana';

        $cities_array = [];

        if($zip_code != 0) {
            $cities = City::find()
                ->where('zip_code = '.$zip_code);
                if($type == 'social') {
                    $cities->andWhere('office_type is null');
                }
            $cities =  $cities->all();
        } else {
            $cities = City::find()
                ->where('name = "'.$city.'"')
                ->andWhere('state = "'.$state.'"')
                ->all();
        }

        if($output == 'id') {
            foreach ($cities as $key => $value) {
                if(!in_array($value->id, $cities_array)){
                    array_push($cities_array, $value->id);
                }
            }

            return implode(',',$cities_array);
        } else {
            foreach ($cities as $key => $value) {
                if(!in_array($value->zip_code, $cities_array)){
                    array_push($cities_array, $value->zip_code);
                }
            }

            return implode(',',$cities_array);
        }
    }

    public function actionGetHQZipCityFromCookie(){
        $cookies = Yii::$app->request->cookies;

        $zip_code = ($cookies->getValue('nw_zipCode')) ? $cookies->getValue('nw_zipCode') : 0;

        $cities_array = [];

        if($zip_code != 0) {
            $cities = City::find()
                ->where('zip_code = '.$zip_code)
                ->andWhere('office_type IS NULL')
                ->one();
        }

        return $cities['id'];
    }

    public function wkt_to_geojson ($text) {
        $decoder = new gisconverter\WKT();
        return $decoder->geomFromText($text)->toGeoJSON();
    }

    public function actionGetBoundariesFromData($zip_codes, $type){
        $query = new Query();
        $cookies = Yii::$app->request->cookies;
        $zip_boundaries_array = array();
        $zip_array = explode(',',$zip_codes);

        $city = ($cookies->getValue('nw_city')) ? $cookies->getValue('nw_city') : '';
        $state = ($cookies->getValue('nw_state')) ? $cookies->getValue('nw_state') : 'Indiana';

        // Get boundaries data from database
        $datas = $query->select('ST_AsText(zip_boundaries.geometry) as geometry, zip_boundaries.zcta5ce10, zip_boundaries.intptlat10, zip_boundaries.intptlon10')
            ->from('zip_boundaries')
            ->where('zcta5ce10 IN ('.$zip_codes.')')
            ->all();

        $returnData = new \stdClass();

        $userId = (Yii::$app->user->id) ? Yii::$app->user->id : 0;

        //Adjusted object params according to api output
        $returnData->type = "FeatureCollection";

        for ($i=0; $i < count($datas); $i++) {
            array_push($zip_boundaries_array,$datas[$i]['zcta5ce10']);

            // Get city details
            $query = new Query();

            $city = $query ->select('c.*, f.status')
                ->from('city c')
                ->leftJoin('favorite f', '(f.user_id = '.$userId.' AND f.city_id = c.id AND f.type = "city")')
                ->where(['c.zip_code' => $datas[$i]['zcta5ce10']])
                ->andwhere(['c.office_type' => null])
                ->one();

            $zip_type = ($city['status'] == '1') ? 'Followed' : $type;

            $returnData->features[$i] = array(
                'type' => 'Feature',
                'properties' => (object)array(
                    'id' => $city['id'],
                    'zipCode' => $datas[$i]['zcta5ce10'],
                    'city' => $city['name'],
                    'state' => $city['state'],
                    'lat' => $city['lat'],
                    'lng' => $city['lng'],
                    'type' => $zip_type
                ),
                'geometry' => json_decode($this->wkt_to_geojson($datas[$i]['geometry']))
            );
        }

        $returnData->remainZip = array_diff($zip_array,$zip_boundaries_array);
        
        return $returnData;
    }

    /**
     * Add general topic under Government community
     * @throws \Exception
     */
    public function actionGovtCommunitiesSetGeneralTopic(){
        $system_user_id = Yii::$app->params['systemUserId'];
        set_time_limit(1800); // Set max execution time 30 minutes.

        $cities = City::find()
            ->where('office_type = "government"')
            ->andWhere('gen_topic_added = 0')
            ->all();

        echo date('Y-m-d H:i:s').'<br/>';
        foreach ($cities as $key => $value) {

            // Create topic and post for communities
            $Topic = new Topic;
            $Topic->city_id = $value->id;
            $Topic->user_id = $system_user_id;
            $Topic->title = 'Local Problem Solving';
            $Topic->save();

            $Post = new Post();
            $Post->title = 'solveproblemstogether';
            $Post->content = "Welcome to the solution center's main line! This line is open for all discussion, just remember that the key to finding answers is seeing all the important angles. Please add a line for new problems that require extensive deliberation.";
            $Post->topic_id = $Topic->id;
            $Post->user_id = $system_user_id;
            $Post->post_type = 1;
            $Post->save();

            $Topic->post_count = 1;
            $Topic->update();

            // Update cities general topic column
            $City = City::findOne($value->id);
            $City->gen_topic_added = 1;
            $City->update();

            echo '<br/> Added general topic for '.$value->id;
        }
        echo '<br/>'.date('Y-m-d H:i:s').'<br/>';
        die();
    }

    /**
     * Add general topic under University community
     * @throws \Exception
     */
    public function actionUniversityCommunitiesSetGeneralTopic(){
        $system_user_id = Yii::$app->params['systemUserId'];
        set_time_limit(1800); // Set max execution time 30 minutes.

        $cities = City::find()
            ->where('office_type = "university"')
            ->andWhere('gen_topic_added = 0')
            ->all();

        echo date('Y-m-d H:i:s').'<br/>';
        foreach ($cities as $key => $value) {

            // Create topic and post for communities
            $Topic = new Topic;
            $Topic->city_id = $value->id;
            $Topic->user_id = $system_user_id;
            $Topic->title = 'How can we make things better?';
            $Topic->save();

            $Post = new Post();
            $Post->title = 'ideas';
            $Post->content = 'Ideas move the world forward, share yours here.';
            $Post->topic_id = $Topic->id;
            $Post->user_id = $system_user_id;
            $Post->post_type = 1;
            $Post->save();

            $Topic->post_count = 1;
            $Topic->update();

            // Update cities general topic column
            $City = City::findOne($value->id);
            $City->gen_topic_added = 1;
            $City->update();

            echo '<br/> Added general topic for '.$value->id;
        }
        echo '<br/>'.date('Y-m-d H:i:s').'<br/>';
        die();
    }

    /**
     * Add general topic under Social community
     * @throws \Exception
     */
    public function actionSocialCommunitiesSetGeneralTopic(){
        $system_user_id = Yii::$app->params['systemUserId'];
        set_time_limit(3600); // Set max execution time 60 minutes.

        $cities = City::find()
            ->where('gen_topic_added = 0')
            ->limit(10000)
            ->all();

        echo date('Y-m-d H:i:s').'<br/>';
        foreach ($cities as $key => $value) {

            // Create topic and post for communities
            $Topic = new Topic;
            $Topic->city_id = $value->id;
            $Topic->user_id = $system_user_id;
            $Topic->title = 'Local community channel';
            $Topic->save();

            $Post = new Post();
            $Post->title = 'Welcome';
            $Post->content = "Welcome to the community center's main chat line! Introduce yourself, explore a bit, help yourself to the fridge and remember, Life is good!";
            $Post->topic_id = $Topic->id;
            $Post->user_id = $system_user_id;
            $Post->post_type = 1;
            $Post->save();

            $Topic->post_count = 1;
            $Topic->update();

            // Update cities general topic column
            $City = City::findOne($value->id);
            $City->gen_topic_added = 1;
            $City->update();

            echo '<br/> Added general topic for '.$value->id;
        }
        echo '<br/>'.date('Y-m-d H:i:s').'<br/>';
        die();
    }

    public function actionGetCommunitiesCountFromZip()
    {
        $zipCode = isset($_GET['zip_code']) ? $_GET['zip_code'] : '';

        $cities = City::find()
            ->where('zip_code = '.$zipCode)
            ->all();

        $communities = sizeof($cities);

        $return = array(
            'communities'=> $communities
        );

        $hash = json_encode($return);
        return $hash;
    }

    public function actionGetBrilliantPostsFromZip()
    {
        $data = array();
        $cities_array = array();

        $zipCode = isset($_GET['zip_code']) ? $_GET['zip_code'] : '';

        $cities = City::find()
            ->where('zip_code = '.$zipCode)
            ->all();

        $communities = sizeof($cities);

        if($communities > 0) {
            foreach ($cities as $key => $value) {
                if(!in_array($value->id, $cities_array)){
                    array_push($cities_array, $value->id);
                }
            }

            $city_ids = implode(',',$cities_array);

            $limit = 6;//Yii::$app->params['LimitObjectHoverPopup'];

            $posts = Post::GetBrilliantPostsByCities($limit, $city_ids);

            foreach($posts as $post){
                $item = [
                    'post_id' => $post['id'],
                    'brilliant' => $post['brilliant_count'] ? $post['brilliant_count'] : 0,
                    'name_post' => $post['title'],
                    'content' => $post['content'],
                    'post_type' => $post['post_type'],
                    'topic_id' => $post['topic_id'],
                ];
                array_push($data, $item);
            }
        }

        $return = array(
            'posts' => $data,
            'communities'=> $communities
        );

        $hash = json_encode($return);
        return $hash;
    }

    public function actionSetGlowCookie()
    {
        $object = $_POST['object'];

        $c = Yii::$app->response->cookies;

        $cookie = new Cookie(['name' => $object, 'value' => 'true', 'expire' => (time() + (365 * 86400))]);
        $c->add($cookie);

        $data = ['success'=> true];
        $data = json_encode($data);
        return $data;
    }

    public function actionGetUserById()
    {
        $user_id = isset(Yii::$app->user->id) ? Yii::$app->user->id : 0;

        if($user_id > 0) {
            $user = User::find()->where(['user.id' => $user_id])->with('profile')->one();


            $item = [
                'user_id' => $user->id,
                'lat' => $user->profile->lat,
                'lng' => $user->profile->lng,
                'zip_code' => $user->profile->zip_code,
                'gender'=> ucfirst($user->profile->gender),
                'dob'=> $user->profile->dob,
                'day'=> date('j', strtotime($user->profile->dob)),
                'month'=> date('n', strtotime($user->profile->dob)),
                'year'=> date('Y', strtotime($user->profile->dob)),
                'first_name'=> $user->profile->first_name,
                'last_name'=> $user->profile->last_name,
                'email'=> $user->email
            ];
            $data = ['success'=> true, 'data' => $item];
        } else {
            $data = ['error'=> true, 'msg' => 'User not logged in'];
        }

        $data = json_encode($data);
        return $data;
    }

    public function actionGetBuildDetailFromZip()
    {
        $data = array();
        $cities_array = array();
        $favourite = '';
        $social = '';

        $zipCode = isset($_GET['zip_code']) ? $_GET['zip_code'] : '';
        $currentUser = isset($_GET['user_id']) ? $_GET['user_id'] : Yii::$app->user->id;

        $cities = City::find()
            ->where('zip_code = '.$zipCode)
            ->all();

        $communities = sizeof($cities);

        if($communities > 0) {
            foreach ($cities as $key => $value) {
                if(!in_array($value->id, $cities_array)){
                    array_push($cities_array, $value->id);
                    if(!$favourite && $currentUser) {
                        $favourite = Favorite::find()->where('user_id = '.$currentUser.' AND city_id = '.$value->id.' AND status = 1')->one();
                    }

                    if(!$social && $value->office_type == null){
                        $social = $value->id;
                    }
                }
            }
            $city_ids = implode(',',$cities_array);
        }

        if($favourite) {
            $user_follow = 'true';
        } else {
            $user_follow = 'false';
        }

        $return = array(
            'user_follow' => $user_follow,
            'social_community' => $social,
            'communities'=> $communities
        );

        $hash = json_encode($return);
        return $hash;
    }
}
