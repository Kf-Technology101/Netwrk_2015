<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use yii\web\Session;
use yii\web\Cookie;
use yii\db\Query;
use yii\helpers\Url;
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
                'hashtag_post'=> $hashtag['count_hash']
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
        $city_ids = $this->actionGetCitiesFromCookie();

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
        $img = '/img/icon/map_icon_community_v_2.png';
        // SELECT COUNT(DISTINCT a.user_id) AS count_user_comment FROM `ws_messages` AS a WHERE post_id = 247;
        // or
        // SELECT COUNT(DISTINCT a.user_id) AS count_user_comment, c.post_id  FROM `ws_messages` as a, post as b, topic as
        // c, city as d WHERE a.post_id=b.id AND b.topic_id=c.id AND c.city_id=d.id GROUP BY a.post_id ORDER BY count_user_comment
        //  DESC LIMIT 10;

        foreach ($cities as $key => $value) {
            if($value->office_type == 'university'){
                $img = '/img/icon/map_icon_university_v_2.png';
            } else if($value->office_type == 'government'){
                $img = '/img/icon/map_icon_government_v_2.png';
            } else {
                $img = '/img/icon/map_icon_community_v_2.png';
            }

            if(isset($value->topics[0])) {
                $post = $this->GetPostMostBrilliant($value->id);
                $user_post = $post['user'];
                $content = $post['content'];
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
                    'mapicon'=>$img,
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
                    'mapicon'=>$img,
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
        $img = '/img/icon/map_icon_community_v_2.png';

        foreach ($cities as $key => $value) {
            if($value->office_type == 'university'){
                $img = '/img/icon/map_icon_university_v_2.png';
            } else if($value->office_type == 'government'){
                $img = '/img/icon/map_icon_government_v_2.png';
            } else {
                $img = '/img/icon/map_icon_community_v_2.png';
            }

            if(isset($value->topics[0])) {
				$post = $this->GetPostMostBrilliant($value->id);
                $user_post = $post['user'];
                $content = $post['content'];
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
                    'mapicon'=>$img,
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
                    'mapicon'=>$img,
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
        $img = '/img/icon/map_icon_community_v_2.png';

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
                if($city->office_type == 'university'){
                    $img = './img/icon/map_icon_university_v_2.png';
                } else if($city->office_type == 'government'){
                    $img = './img/icon/map_icon_government_v_2.png';
                } else {
                    $img = '/img/icon/map_icon_community_v_2.png';
                }

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
                    'mapicon'=>$img,
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
                    'mapicon'=>$img,
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
        $img = '/img/icon/map_icon_community_v_2.png';

        $netwrk = array(
                'id'=> $city->id,
                'name'=> $city->name,
                'lat'=> $city->lat,
                'lng'=> $city->lng,
                'zip_code'=> $city->zip_code,
                'office'=>$city->office,
                'office_type'=>$city->office_type,
                'topic' => '',
                'mapicon'=>$img,
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
        $request = Yii::$app->request->isAjax;

        $cookies = Yii::$app->request->cookies;

        if($request){
            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            $city_ids = $this->actionGetCitiesFromCookie();

            $top_post = Post::GetTopPostUserJoinGlobal($limit,null,$city_ids);
            $top_topic = Topic::GetTopTopicGlobal($limit, null,$city_ids);
            $top_city = City::GetTopCityUserJoinGlobal($limit,$city_ids);
            $top_communities = City::TopHashTag_City($top_city,$limit);

            // If user is logged in then get his followed communities feeds
            if(Yii::$app->user->id) {
                $feeds = json_decode($this->actionGetFeedByUser(), true);
            }
            // else get the feeds for the communities from zip or city entered on cover page
            else {
                $feeds = json_decode($this->actionGetFeedByCities($city_ids), true);
            }

            $item = [
                'top_post'=> $top_post,
                'top_topic'=> $top_topic,
                'top_communities'=> $top_communities,
                'feeds' => $feeds
            ];

            $hash = json_encode($item);
            return $hash;
        }
    }

    public function actionLandingPage()
    {
        return $this->render($this->getIsMobile() ? 'mobile/landing_page' : '');
    }

    public function actionGetGroupsLoc($groupId = null) {
        $maxlength = Yii::$app->params['MaxlengthContent'];

        $query = new Query();
        //selecting all coordinates with related
        $datas = $query->select('COUNT(DISTINCT ws_messages.user_id) AS count_user_comment, group.id, group.name, group.latitude, group.longitude, COUNT(post.id) AS post_count')
            ->from('group')
            //todo: make normally
            ->where("group.latitude is not null and group.longitude is not null and group.city_id is null" . (!is_null($groupId) ? " and group.id = " . $groupId : ""))
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
                ->orderBy(['created_at'=> SORT_DESC])
                ->limit(20);

            //todo: pagination on history feed
            $data_feed = $history_feed->all();

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
                $feeds[$value->city_id][] = $item;
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

    public function actionGetZipBoundaries()
    {
        $result = array();
        $cookies = Yii::$app->request->cookies;

        $city = ($cookies->getValue('nw_city')) ? $cookies->getValue('nw_city') : '';
        $state = ($cookies->getValue('nw_state')) ? $cookies->getValue('nw_state') : 'Indiana';

        $zip_code = $this->actionGetCitiesFromCookie('zip_codes');

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

        $data = (array)(json_decode($result));
        //$data = (json_encode($data[0]->geometry->coordinates));

        $return = [];
        $returnData = new \stdClass();
        //Adjusted object params according to purchased api
        $returnData->type = "FeatureCollection";
        foreach ($data as $key => $value) {
            $returnData->features[$key] = array(
                'type' => 'Feature',
                'properties' => (object)array(
                    'zipCode' => $data[$key]->properties->ZCTA5CE10,
                    'city' => $city,
                    'state' => $state
                ),
                'geometry' => (object)array(
                    'type' => $data[$key]->geometry->type,
                    'coordinates' => $data[$key]->geometry->coordinates
                )
            );
        }
        die(json_encode($returnData));
    }

    public function actionGetFeedByCities($cities = array()) {
        $request = 1;//Yii::$app->request->isAjax;

        if($request){

            $limit = Yii::$app->params['LimitObjectFeedGlobal'];

            //fetch history feed of users favorite cities
            $htf = new HistoryFeed();
            $history_feed = $htf->find()->select('history_feed.*, city.zip_code')
                ->join('INNER JOIN', 'city', 'city.id = history_feed.city_id')
                ->where('city_id IN ('.$cities.')')
                ->orderBy(['created_at'=> SORT_DESC]);

            //todo: pagination on history feed
            $data_feed = $history_feed->all();

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
                $feeds[$value->city_id][] = $item;
            }

            $hash = json_encode($feeds);
            return $hash;
        }
    }

    public function actionGetCitiesFromCookie($output = 'id'){
        $cookies = Yii::$app->request->cookies;

        $zip_code = ($cookies->getValue('nw_zipCode')) ? $cookies->getValue('nw_zipCode') : 0;
        $city = ($cookies->getValue('nw_city')) ? $cookies->getValue('nw_city') : '';
        $state = ($cookies->getValue('nw_state')) ? $cookies->getValue('nw_state') : 'Indiana';

        $cities_array = [];

        if($zip_code != 0) {
            $cities = City::find()
                ->where('zip_code = '.$zip_code)
                ->all();
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
}
