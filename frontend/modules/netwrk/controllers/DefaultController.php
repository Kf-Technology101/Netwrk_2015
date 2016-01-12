<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use yii\web\Session;
use yii\db\Query;
use yii\helpers\Url;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\components\UtilitiesFunc;

class DefaultController extends BaseController
{

    public function actionIndex()
    {
        return $this->render($this->getIsMobile() ? 'mobile/index' : 'index');
    }

    public function actionGetUserProfile()
    {
        if (Yii::$app->user->id) {
            $user = User::find()->where('id ='.Yii::$app->user->id)->with('profile')->one();
        }

        if ($user->profile->photo == null){
            $image = 'img/icon/no_avatar.jpg';
        }else{
            $image = 'uploads/'.$user->id.'/'.$user->profile->photo;
        }

        $data = [
                'user_id'=> $user->id,
                'name'=> $user->profile->first_name." ".$user->profile->last_name,
                'avatar'=> $image,
                'created_date' => $user->create_time
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
        $city = City::find()->where(['zip_code'=>$zipcode, 'office'=>$place_name])->one();

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
    protected static function Top4Topices($city)
    {

        $topices = Topic::Get4Topic($city);
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

    //Get Similarpost and trending number on top 4 post
    protected function Trending4Post($city)
    {
        $hashtag = [];
        foreach ($city->topics as $topic){
            foreach ($topic->posts as $post) {
                # code...
                $arr = explode(' ',trim($post->title));
                $item = [
                    'post_id'=> $post->id,
                    'post_name'=> $arr[0],
                    'post_trending'=> Post::SearchHashTagPost($arr[0],$city->id)
                ];
                array_push($hashtag, $item);
            }
        }
        $data = $this->GetTop4Trending($hashtag);

        return $data;
    }

    //Sort 4 post have trending most
    protected static function GetTop4Trending($hashtag)
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

        $orderby = "post_trending";
        array_multisort($sortArray[$orderby],SORT_DESC,$hashtag);

        return array_slice($hashtag, 0, 4);
    }

    protected static function GetPostMostBrilliant($city)
    {
        $post = Post::GetPostMostBrilliant($city);
        $item = [
                    'post_id'=>$post->id,
                    'brilliant'=>$post->topic->brilliant_count ? $post->topic->brilliant_count : 0,
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
        $maxlength = Yii::$app->params['MaxlengthContent'];

        $query = new Query();
        $datas = $query->select('COUNT(DISTINCT ws_messages.user_id) AS count_user_comment, city.id, COUNT(post.id) AS post_count')
            ->from('city')
            ->leftJoin('topic', 'city.id=topic.city_id')
            ->leftJoin('post', 'topic.id=post.topic_id')
            ->leftJoin('ws_messages', 'post.id=ws_messages.post_id')
            ->groupBy('city.id')
            ->orderBy('count_user_comment DESC, post_count DESC')
            ->limit(10)
            ->all();
        $zipcodes = array();
        for ($i=0; $i < count($datas); $i++) {
            array_push($zipcodes, $datas[$i]['id']);
        }
        // $cities = City::find()->with('topics.posts')->orderBy(['user_count'=> SORT_DESC,'post_count'=> SORT_DESC])->limit(10)->all();
        $cities = City::find()->with('topics.posts')->where(['id' => $zipcodes])->all();

        $data = [];

        foreach ($cities as $key => $value) {
            if(isset($value->topics[0])) {
                $post = $this->GetPostMostBrilliant($value->id);
                $user_post = $post['user'];
                $content = $post['content'];
                $topices = $this->Top4Topices($value->id);
                $trending = $this->Trending4Post($value);

                // if(strlen($content) > $maxlength ){
                //     $content = substr($post->content,0,$maxlength) ;
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
                    'trending_post'=> $trending,
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
        $maxlength = Yii::$app->params['MaxlengthContent'];
        $cities = City::find()->with('topics.posts')->orderBy(['post_count'=> SORT_DESC])->all();

        $data = [];

        foreach ($cities as $key => $value) {
            if(isset($value->topics[0])) {
                $post = $value->topics[0]->posts[0];
                $content = $post->content;
                $user_post = $post->user;
                $content = $post->content;
                $topices = $this->Top4Topices($value->id);
                $trending = $this->Trending4Post($value);

                if(strlen($content) > $maxlength ){
                    $content = substr($post->content,0,$maxlength ) ;
                    $content = $content."...";
                }

                $netwrk = array(
                    'id'=> $value->id,
                    'name'=> $value->name,
                    'lat'=> $value->lat,
                    'lng'=>$value->lng,
                    'zip_code'=> $value->zip_code,
                    'office'=>$value->office,
                    'office_type'=>$value->office_type,
                    'topic'=> $topices,
                    'trending_post'=> $trending,
                    'user'=>[
                        'username'  => $user_post->profile->first_name." ".$user_post->profile->last_name,
                        'avatar'    => $user_post->profile->photo ? Url::to('@web/uploads/'.$user_post->id.'/'.$user_post->profile->photo) : Url::to('@web/img/icon/no_avatar.jpg'),
                        'work'      => $user_post->profile->work,
                        'zipcode'   => $user_post->profile->zip_code,
                        'place'     => $user_post->profile->city->name
                    ],
                    'post'=>[
                        'post_id'=>$post->id,
                        'brilliant'=>$post->brilliant_count ? $post->brilliant_count : 0,
                        'name_post'=> $post->title,
                        'content' => $content,
                        'topic_id' => $post->topic_id,
                    ]
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

    public function actionPlaceSave(){
        $zipcode = $_POST['zip_code'];
        $city_name = $_POST['netwrk_name'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $office = $_POST['office'];
        $office_type = $_POST['office_type'];

        $netwrk = new City;
        $netwrk->name = $city_name;
        $netwrk->lat = $lat;
        $netwrk->lng = $lng;
        $netwrk->zip_code = $zipcode;
        $netwrk->office = $office;
        $netwrk->office_type = $office_type;
        $netwrk->save();
        return json_encode($netwrk->id);
    }
}
