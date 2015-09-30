<?php 
namespace frontend\modules\netwrk\controllers;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\UserMeet;
use yii\helpers\Url;

class SettingController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('mobile/index');
    }

    public function actionLoadProfile()
    {
        $currentUser = 1;
        $current_date = date('Y-m-d H:i:s');
        $user = User::find()->where('id ='.$currentUser)->one();

        if($user && $user->profile){

            $years = $user->profile->age;
            // $time1 = date_create($years);
            // $time2 = date_create($current_date);
            // $year_old = $time1->diff($time2)->y;

            if ($user->profile->photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                //get avatar
            }

            $info = array(
                'status' => 1,
                'age'=> $years,
                'work'=> $user->profile->work,
                'image' => $image,
                'about'=> $user->profile->about,
            );
            
        }else{
            $info = array(
                'status' => 0
            );
        }    
        $hash = json_encode($info);
        return $hash;          
    }

    public function actionUpdateProfile()
    {
        $currentUser = 1;
        $status = 0;

        $age = $_POST['age'];
        $work = $_POST['work'];
        $about = $_POST['about'];
        $user = User::find()->where('id ='.$currentUser)->one();
        $profile = $user->profile;

        $user->profile->age = $age;
        $user->profile->work = $work;
        $user->profile->about = $about;

        $user->profile->update();
    }
}

?>