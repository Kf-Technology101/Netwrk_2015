<?php 

namespace frontend\modules\netwrk\controllers;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\UserMeet;
use yii\helpers\Url;

class MeetController extends BaseController
{
    public function actionIndex() 
    {   
        return $this->render('mobile/index');
    }

    public function actionGetUserMeet()
    {
        $userCurrent = 1;
        $Auth = $_GET['user_id'];
        $gender = $_GET['gender'];
        $distance = $_GET['distance'];
        $age = $_GET['age'];
        $current_date = date('Y-m-d H:i:s');

        if($Auth > 0){

        }else{
            $users = User::find()
                            ->addSelect(["*", "RAND() order_num"])
                            ->where('id !='.$userCurrent)
                            ->orderBy(['order_num'=> SORT_DESC])
                            ->all();
        }

        $data = [];
        foreach ($users as $key => $value) {

            $posts = Post::find()->where('user_id ='.$value->id)->orderBy(['created_at'=> SORT_DESC])->limit(4)->all();
            $post_data = [];

            foreach ($posts as $key => $post){
                array_push($post_data,'#'.$post->title);
            }
            $usermeet = UserMeet::find()->where('user_id_1 ='.$userCurrent.' AND user_id_2='.$value->id)->one();
            if($usermeet && $usermeet->status == 1){
                $meet = 1;
            }else{
                $meet = 0;
            }
            if ($value->profile->photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                $image = Url::to('@web/uploads/'.$value->id.'/'.$value->profile->photo);
            }

            $years = $value->profile->age;

            // $time1 = date_create($years);
            // $time2 = date_create($current_date);
            // $year_old = $time1->diff($time2)->y;

            $user = array(
                'user_id' => $value->id,
                'username'=> $value->username,
                'met' => $meet,
                'information'=> array(
                    'image'=> $image,
                    'year_old'=> $years,
                    'work'=> $value->profile->work,
                    'about'=> $value->profile->about,
                    'post'=> $post_data,
                ),
            );
            array_push($data,$user);
        }

        $temp = array ('data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }

    public function actionUserMeet()
    {   
        $userCurrent = 1;
        $Auth = $_GET['user_id'];

        $usermeet = UserMeet::find()->where('user_id_1 ='.$userCurrent.' AND user_id_2='.$Auth)->one();

            
        if($usermeet == null){
            $meet = new UserMeet;
            $meet->user_id_1 = $userCurrent;
            $meet->user_id_2 = $Auth;
            $meet->status = 1;
            $meet->save();
        }else{
            $usermeet->status = 1;
            $usermeet->update();
        }
    }

    public function actionUserMet()
    {
        $userCurrent = 1;
        $Auth = $_GET['user_id'];
        $meet = UserMeet::find()->where('user_id_1 ='.$userCurrent.' AND user_id_2='.$Auth)->one();
        $meet->status = 0;
        $meet->update();
    }
}

?>