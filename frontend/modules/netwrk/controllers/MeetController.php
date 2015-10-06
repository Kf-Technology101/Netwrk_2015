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

        $current_date = date('Y-m-d H:i:s');
        $userLogin = User::findOne($userCurrent);
        $users = User::find()
                        ->addSelect(["*", "RAND() order_num"])
                        ->where('id !='.$userCurrent)
                        ->orderBy(['order_num'=> SORT_DESC])
                        ->all();

        if($userLogin->setting){
            $filter = true;
        }else{
            $filter = false;
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

            $years = $value->profile->dob;

            $time1 = date_create($years);
            $time2 = date_create($current_date);
            $year_old = $time1->diff($time2)->y;

            $distance = $this->get_distance($userLogin->profile->lat,$userLogin->profile->lng,$value->profile->lat,$value->profile->lng);
            
            $user = array(
                'user_id' => $value->id,
                'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                'met' => $meet,
                'distance'=> $distance,
                'information'=> array(
                    'image'=> $image,
                    'year_old'=> $year_old,
                    'work'=> $value->profile->work,
                    'about'=> $value->profile->about,
                    'post'=> $post_data,
                ),
            );

            if( $filter ){
                if($userLogin->setting->gender == 'All'){
                    $gender = true;
                }elseif($value->profile->gender == $userLogin->setting->gender){
                    $gender = true;
                }else{
                    $gender = false;
                }

                if($userLogin->setting->age == 0){
                    $age = true;
                }elseif($year_old >= $userLogin->setting->age){
                    $age = true;
                }else{
                    $age = false;
                }

                if($userLogin->setting->distance == 0){
                    $status_distance = true;
                }elseif($distance <=  $userLogin->setting->distance){
                    $status_distance = true;
                }else{
                    $status_distance = false;
                }
            }

            

            if($filter && $gender && $age && $status_distance ){
                array_push($data,$user);
            }elseif (!$filter){
                array_push($data,$user); 
            }
        }

        $temp = array ('data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }

    public function get_distance($lat1, $lon1, $lat2, $lon2) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        // $unit = strtoupper($unit);

        // if ($unit == "K") {
        //     return ($miles * 1.609344);
        // } else if ($unit == "N") {
        //     return ($miles * 0.8684);
        // } else {
        //     return $miles;
        // }

        return $miles;
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