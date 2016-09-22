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

class SearchController extends BaseController
{

    public function actionGlobalSearch(){
        $_search = $_POST['text'];

        if (Yii::$app->user->isGuest) {
    		$cur_lat = $_POST['lat'] ? $_POST['lat'] : 0;
    		$cur_lng = $_POST['lng'] ? $_POST['lng'] : 0;
        }else{
            $current_user = Yii::$app->user->identity;
            $cur_lat = $current_user->profile->lat;
            $cur_lng = $current_user->profile->lng;
        }
        $num_search_local = 0;
        $num_search_global = 0;
        $radius_local = 50;

        // Search local in radius 50 miles in current location
        $id_local =[];
        $post_local = [];
        $topic_local = [];
        $netwrk_local = [];

         // Search global all topic and netwrk except result search Local
        $id_global =[];
        $post_global = [];
        $topic_global = [];
        $netwrk_global = [];

        $maxlength = Yii::$app->params['MaxlenghtContentPostDesktop'];
        $maxlengthMobile = Yii::$app->params['MaxlenghtContentPostMobile'];
        $limitResult = Yii::$app->params['LimitResultSearch'];

		// Post Local
        $type_local = 'local';
        $posts = Post::SearchPost($_search,$type_local,$id_local);

        foreach ($posts as $key => $post) {
        	$distance = UtilitiesFunc::CalculatorDistance($cur_lat,$cur_lng,$post->topic->city->lat,$post->topic->city->lng);
            if($distance <= $radius_local && count($post_local) < $limitResult){

	            if($this->getIsMobile() && strlen($post->content) > $maxlengthMobile){
	                $post->content = substr($post->content,0,$maxlengthMobile) ;
	                $post->content = $content." ...<span class='show_more'>show more</span>";
	            }elseif(!$this->getIsMobile() && strlen($post->content) > $maxlength){
	                $post->content = substr($post->content,0,$maxlength) ;
	                $post->content = $post->content." ...<span class='show_more'>show more</span>";
	            }

                if ($post->user->profile->photo == null){
                    $image = Url::to('@web/img/icon/no_avatar.jpg');
                }else{
                    $image = Url::to('@web/uploads/'.$post->user->id.'/'.$post->user->profile->photo);
                }

                $item_post = [
                    'id'=>$post->id,
                    'thumb'=> $image,
                    'title'=>$post->title,
                    'content'=> $post->content,
                    'brilliant'=> $post->brilliant_count ? $post->brilliant_count : 0,
                    'created_at'=> date_format(date_create($post->created_at),'m/d/Y')
                ];

                array_push($post_local, $item_post);
                array_push($id_local,$post->topic->city->id);
                $num_search_local += count($post_local);
            }
       	}

       	// Topic Local
       	/*$topics = Topic::SearchTopic($_search,$type_local,$id_local);
        foreach ($topics as $key => $topic) {
        	$distance = UtilitiesFunc::CalculatorDistance($cur_lat,$cur_lng,$topic->city->lat,$topic->city->lng);
            if($distance <= $radius_local && count($post_local) < $limitResult){
                $item_post = [
                    'id'=>$topic->id,
                    'title'=>$topic->title,
                    'post'=>$topic->post_count,
                    'city_id'=> $topic->city->id,
                    'city_name'=>$topic->city->name
                ];
                array_push($topic_local, $item_post);
                array_push($id_local,$topic->city->id);
                $num_search_local += count($topic_local);
            }
       	}

        $city_local = City::SearchCity($id_local,$type_local,$id_global);
    	foreach ($city_local as $key => $value) {
            $distance = UtilitiesFunc::CalculatorDistance($cur_lat,$cur_lng,$value->lat,$value->lng);
            if($distance <= $radius_local){
                $netwrk = [
                    'id'=>$value->id,
                    'name'=> $value->name,
                    'zipcode'=> $value->zip_code,
                ];
                array_push($netwrk_local, $netwrk);
                $num_search_local += count($netwrk_local);
            }
        }*/
        $type_global ='global';
        $posts_go = Post::SearchPost($_search,$type_global,$id_local);

		foreach ($posts_go as $key => $post_go) {

            if($this->getIsMobile() && strlen($post_go->content) > $maxlengthMobile){
                $post_go->content = substr($post_go->content,0,$maxlengthMobile) ;
                $post_go->content = $post_go->content." ...<span class='show_more'>show more</span>";
            }elseif(!$this->getIsMobile() && strlen($post_go->content) > $maxlength){
                $post_go->content = substr($post_go->content,0,$maxlength) ;
               	$post_go->content = $post_go->content." ...<span class='show_more'>show more</span>";
            }

            if ($post_go->user->profile->photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                $image = Url::to('@web/uploads/'.$post_go->user->id.'/'.$post_go->user->profile->photo);
            }
			$item = [
                'id'=>$post_go->id,
                'thumb'=>$image,
                'title'=>$post_go->title,
                'content'=> $post_go->content,
                'brilliant'=> $post_go->brilliant_count ? $post_go->brilliant_count : 0,
                'created_at'=> date_format(date_create($post_go->created_at),'m/d/Y')
			];
			array_push($post_global, $item);
			array_push($id_global,$post_go->topic->city->id);
			$num_search_global += count($post_global);
		}

		/*$topics_go = Topic::SearchTopic($_search,$type_global,$id_local);

		foreach ($topics_go as $key => $topic_go) {
			$item = [
                'id'=>$topic_go->id,
                'title'=>$topic_go->title,
                'post'=>$topic_go->post_count,
                'city_id'=> $topic_go->city->id,
                'city_name'=>$topic_go->city->name
			];
			array_push($topic_global, $item);
			array_push($id_global,$topic_go->city->id);
			$num_search_global += count($topic_global);
		}

		$city_go = City::SearchCity($_search,$type_global,$id_local);

		foreach ($city_go as $key => $value) {
			$netwrk = [
                'id'=>$value->id,
                'name'=> $value->name,
                'zipcode'=> $value->zip_code,
            ];
            array_push($netwrk_global, $netwrk);
            $num_search_global += count($netwrk_global);
		}*/

        $temp =[
        	'search_local'=>$num_search_local,
        	'search_global'=> $num_search_global,
        	'local'=>[
        		'post'=> $post_local,
	            'topic'=>$topic_local,
	            'netwrk'=>$netwrk_local
        	],
        	'global'=>[
        		'post'=> $post_global,
	            'topic'=>$topic_global,
	            'netwrk'=>$netwrk_global
        	]
        ];

        $hash = json_encode($temp);
        return $hash;
    }

    public function actionCoverSearch(){
        $search = $_POST['text'];
        $search_results = [];

        $results = City::SearchCover($search);

		foreach ($results as $key => $value) {
            if(is_numeric($search)) {
                $city = [
                    'zipCode'=> $value->zip_code,
                ];
            } else {
                $city = [
                    'name'=> $value->name,
                    'state'=> $value->state,
                    'stateAbbr'=> $value->state_abbreviation
                ];
            }

            array_push($search_results, $city);
        }

        if(is_numeric($search)) {
            array_unique($search_results);
            $type = 'zip_code';
        } else {
            $type = 'city';
        }

        $temp =[
            'cover_result' => $search_results,
            'result_type' => $type
        ];

        $hash = json_encode($temp);
        return $hash;
    }
}