<?php
namespace console\controllers;

use Yii;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Post;

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
}