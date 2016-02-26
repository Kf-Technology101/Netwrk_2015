<?php
namespace console\controllers;

use Yii;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\HistoryFeed;

class HelperController extends \yii\console\Controller{

	public function actionUpdateTimeChat(){
        $posts = Post::find()->all();

        if ($posts) {
            foreach ($posts as $key => $value) {
                $value->chat_updated_time = $value->updated_at;
                if($value->update()){
                	echo "Update post {$value->id} complete \n";
                }else{
                	echo "Update post {$value->id} failed \n";
                }
            }
        }
	}

    public function actionUpdateHashtagOnPost(){
        $posts = Post::find()->all();

        foreach ($posts as $key => $post) {
            Post::CreateHashtag($post);
        }
    }

    //Update data history feed
    public function actionUpdateHistoryFeed(){
        $topicies = Topic::find()->all();

        foreach ($topicies as $key => $topic) {
            $hft = new HistoryFeed();
            $hft->id_item = $topic->id;
            $hft->type_item = 'topic';
            $hft->city_id = $topic->city_id;
            $hft->created_at = $topic->created_at;
            if($hft->save(false)){
                echo "create history feed topic {$topic->id} \n";
            }
            $posts = $topic->posts;
            foreach ($posts as $key => $post) {
                $hfp = new HistoryFeed();
                $hfp->id_item = $post->id;
                $hfp->type_item = 'post';
                $hfp->city_id = $post->topic->city_id;
                $hfp->created_at = $post->created_at;
                if($hfp->save(false)){
                    echo "create history feed post {$post->id} \n";
                }
            }
        }
    }

}