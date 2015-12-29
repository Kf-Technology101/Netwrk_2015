<?php

namespace frontend\modules\netwrk\controllers;

use Yii;
use yii\web\Session;
use yii\db\Query;
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
        $posts = Post::find()->where(['like','title',$_search])->andWhere('topic_id != NULL')->orderBy(['brilliant_count'=> SORT_DESC])->all();
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

                $item_post = [
                    'id'=>$post->id,
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
       	$topics = Topic::find()->where(['like','title',$_search])->orderBy(['brilliant_count'=> SORT_DESC])->all();
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

        $city_local = City::find()->where(['id'=> $id_local])->orderBy(['brilliant_count'=> SORT_DESC])->limit($limitResult)->all();
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
        }

        $posts_go = Post::find()->joinWith('topic')
			        ->where(['like','post.title',$_search])
			        ->andWhere(['not in','topic.city_id',$id_local])
			        ->orderBy(['brilliant_count'=> SORT_DESC])
			        ->limit($limitResult)
			        ->all();

		foreach ($posts_go as $key => $post_go) {

            if($this->getIsMobile() && strlen($post_go->content) > $maxlengthMobile){
                $post_go->content = substr($post_go->content,0,$maxlengthMobile) ;
                $post_go->content = $post_go->content." ...<span class='show_more'>show more</span>";
            }elseif(!$this->getIsMobile() && strlen($post_go->content) > $maxlength){
                $post_go->content = substr($post_go->content,0,$maxlength) ;
               	$post_go->content = $post_go->content." ...<span class='show_more'>show more</span>";
            }

			$item = [
                'id'=>$post_go->id,
                'title'=>$post_go->title,
                'content'=> $post_go->content,
                'brilliant'=> $post_go->brilliant_count ? $post_go->brilliant_count : 0,
                'created_at'=> date_format(date_create($post_go->created_at),'m/d/Y')
			];
			array_push($post_global, $item);
			array_push($id_global,$post_go->topic->city->id);
			$num_search_global += count($post_global);
		}

		$topics_go = Topic::find()
					->where(['like','title',$_search])
			        ->andWhere(['not in','city_id',$id_local])
			        ->orderBy(['brilliant_count'=> SORT_DESC])
			        ->limit($limitResult)
			        ->all();

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

		$city_go = City::find()->where(['id'=>$id_global])->andwhere(['not in','id',$id_local])->orderBy(['brilliant_count'=> SORT_DESC])->limit($limitResult)->all();

		foreach ($city_go as $key => $value) {
			$netwrk = [
                'id'=>$value->id,
                'name'=> $value->name,
                'zipcode'=> $value->zip_code,
            ];
            array_push($netwrk_global, $netwrk);
            $num_search_global += count($netwrk_global);
		}

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
}