<?php
namespace console\controllers;

use Yii;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Post;

class HelperController extends \yii\console\Controller{

	public function actionUpdateTimeChat(){
        $post = POST::find()->all();

        if ($post) {
            foreach ($post as $key => $value) {
                $value->chat_updated_time = $value->updated_at;
                if($value->update()){
                	echo "Update post {$value->id} complete \n";
                }else{
                	echo "Update post {$value->id} failed \n";
                }
            }
        }
	}
}