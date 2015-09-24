<?php 

namespace frontend\modules\netwrk\controllers;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profiles;

class MeetController extends BaseController
{
    public function actionIndex() 
    {   
        $user = User::find()
                            // ->joinWith('profiles', '`profiles`.`user_id` = `users`.`id`')
                            ->where('id = 1')->one();
        // var_dump($user);
        var_dump($user->post);die;
        return $this->render('index');
    }

    public function actionGetUserMeet()
    {
        $userCurrent = 1;
        $Auth = $_GET['user_id'];
        $gender = $_GET['gender'];
        $distance = $_GET['distance'];
        $age = $_GET['age'];

        if($Auth > 0){

        }else{
            $users = User::find()->where('id !='.$userCurrent)->all();
        }

        $data = [];
        foreach ($users as $key => $value) {
            $topic = array(

                );
        }

        $temp = array ('data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }
}

?>