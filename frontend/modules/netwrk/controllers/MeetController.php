<?php

namespace frontend\modules\netwrk\controllers;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\UserMeet;
use frontend\modules\netwrk\models\Vote;
use frontend\modules\netwrk\models\ChatPrivate;
use frontend\modules\netwrk\models\WsMessages;
use yii\helpers\Url;
use Yii;

class MeetController extends BaseController
{
    public function actionIndex()
    {
        // if (Yii::$app->user->isGuest) {
        //     return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/meet']);
        // }
        if($this->getIsMobile()){
             return $this->render('mobile/index');
        }else{
            return $this->render('@frontend/modules/netwrk/views/marker/popup_marker_content');
        }
    }

    protected function MixRandUser()
    {
        $system_user_id = Yii::$app->params['systemUserId'];
        $userCurrent = Yii::$app->user->id;
        $user_meet_rand = [];

        // get list user meet owner
        $list_user_meet_owner = UserMeet::find()
                                        ->addSelect('user_id_1')
                                        ->where('user_id_2 = ' . $userCurrent . ' AND status = 1')
                                        ->all();
        $lmo = [];
        if($list_user_meet_owner){
            foreach ($list_user_meet_owner as $key => $value) {
                array_push($lmo, $value->user_id_1);
            }
        }

        $list_user_owner_meet = UserMeet::find()
                                        ->addSelect('user_id_2')
                                        ->where('user_id_1 = ' . $userCurrent . ' AND status = 1')
                                        ->all();
        $luom = [];
        if($list_user_owner_meet){
            foreach ($list_user_owner_meet as $key => $value) {
                if($value->user_id_2 != $userCurrent){
                    array_push($luom, $value->user_id_2);
                }
            }
        }

        // remove user is met owner
        $r = [];
        foreach ($luom as $key => $value){
            if(($k = array_search($value, $lmo)) !== false){
                array_push($r, $lmo[$k]);
                unset($lmo[$k]);
            }
        }

        $list_met = $userCurrent;
        if(count($lmo) > 0){
            $list_meet_owner = implode(',', $lmo);
            $list_met .= ',' . $list_meet_owner;
            // get rand user meet owner
            $user_meet_rand = User::find()
                        ->addSelect(["*", "RAND() order_num"])
                        ->where('id in ('.$list_meet_owner. ')')
                        ->andWhere(['not',['id'=>$system_user_id]])
                        ->with('profile')
                        ->orderBy(['order_num'=> SORT_DESC])
                        ->all();
        }
        if(count($luom) > 0){
            $list_owner_meet = implode(',', $luom);
            // $list_met .= ',' . $list_owner_meet;
        }

        $l = $userCurrent;
        if(count($r) > 0){
            $lst = implode(',', $r);
            $l.= ',' . $lst;
        }
        // get rand other
        $users = User::find()
                    ->addSelect(["*", "RAND() order_num"])
                    ->where('id NOT IN ('.$l.')')
                    ->andWhere(['not',['id'=>$system_user_id]])
                    ->with('profile')
                    ->orderBy(['order_num'=> SORT_DESC])
                    ->all();
        // collect meet user
        for ($i=0; $i < count($users); $i++) {
            array_push($user_meet_rand, $users[$i]);
        }
        $tmp; $index;
        for ($i=0; $i < count($user_meet_rand) - 1; $i++) {
            $index = $i + 2;
            if($i == count($user_meet_rand) - 2){
                $index = 0;
            }
            if($user_meet_rand[$i]->id == $user_meet_rand[$i + 1]->id){
                $tmp = $user_meet_rand[$i + 1];
                $user_meet_rand[$i + 1] = $user_meet_rand[$index];
                $user_meet_rand[$index] = $tmp;
            }
        }

        return $user_meet_rand;
    }

    public function actionGetUserMeet()
    {
        $system_user_id = Yii::$app->params['systemUserId'];

        $current_date = date('Y-m-d H:i:s');
        $filter = false;
        if(Yii::$app->user->isGuest){
            $filter = false;
            $userCurrent = 0;
            $users_rand = User::find()
                                ->addSelect(["*", "RAND() order_num"])
                                ->where(['not',['id'=>$system_user_id]])
                                ->orderBy(['order_num'=> SORT_DESC])
                                ->all();

        }else{
            $userLogin = User::findOne(Yii::$app->user->id);
            if($userLogin->setting){
                $filter = true;
            }
            $users_rand = $this->MixRandUser();
            $userCurrent = Yii::$app->user->id;
        }

        $data = [];
        foreach ($users_rand as $key => $value) {

            $posts = Post::find()->where('user_id ='.$value->id)->andWhere(['not',['topic_id'=> NULL]])->orderBy(['created_at'=> SORT_DESC])->limit(4)->all();
            $post_data = [];

            foreach ($posts as $key => $post){
                $item_post = array(
                    'id'=> $post->id,
                    'title'=> '#'.$post->title
                );
                array_push($post_data,$item_post);
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
            if(Yii::$app->user->isGuest){
                $distance = 0;
            }else{
                $distance = $this->get_distance($userLogin->profile->lat,$userLogin->profile->lng,$value->profile->lat,$value->profile->lng);
            }

            $count_like = 0;
            $count_posts = Post::find()->where('user_id ='.$value->id)->all();
            foreach ($count_posts as $key => $post) {
                $vote = Vote::find()->where('post_id = ' . $post->id . ' AND status = 1')->count();
                if($vote>0){
                    $count_like += $vote;
                }
            }
            $brilliant = $count_like;

            $user = array(
                'user_id' => $value->id,
                'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                'met' => $meet,
                'distance'=> $distance,
                'information'=> array(
                    'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                    'image'=> $image,
                    'year_old'=> $year_old,
                    'work'=> $value->profile->work,
                    'about'=> $value->profile->about,
                    'post'=> $post_data,
                    'brilliant'=>$brilliant,
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

        return $miles;
    }

    public function actionUserMeet()
    {
        $userCurrent = Yii::$app->user->id;
        $Auth = $_GET['user_id'];

        $usermeet = UserMeet::find()->where('user_id_1 ='.$userCurrent.' AND user_id_2='.$Auth. ' AND status = 1')->one();

        if($usermeet == null){
            $meet = new UserMeet;
            $meet->user_id_1 = $userCurrent;
            $meet->user_id_2 = $Auth;
            $meet->status = 1;
            $meet->save();
            $usermeet_guest = Usermeet::find()->where('user_id_1 = '. $Auth. ' AND user_id_2 = '.$userCurrent. ' AND status = 1')->one();
            if($usermeet_guest) {
                $post_private = new POST();
                $post_private->title = 'private'.time();
                $post_private->content = 'content private'.time();
                $post_private->user_id = $userCurrent;
                $post_private->post_type = 0;
                if($post_private->save(false)) {
                    $chat_private = new ChatPrivate();
                    $chat_private->user_id = $userCurrent;
                    $chat_private->user_id_guest = $Auth;
                    $chat_private->post_id = $post_private->id;
                    $chat_private->save(false);
                    $chat_private = new ChatPrivate();
                    $chat_private->user_id = $Auth;
                    $chat_private->user_id_guest = $userCurrent;
                    $chat_private->post_id = $post_private->id;
                    $chat_private->save(false);
                } else {
                    return false;
                }
            }

        }else{
            $usermeet->status = 1;
            $usermeet->update();
        }
    }

    public function actionUserMet()
    {
        $userCurrent = Yii::$app->user->id;
        $Auth = $_GET['user_id'];
        $meet = UserMeet::find()->where('user_id_1 ='.$userCurrent.' AND user_id_2='.$Auth)->one();
        $meet->status = 0;
        $meet->update();
    }

    public function actionGetUserMeetProfile(){

        $chat_post_id = $_POST['post_id'];
        $user_meet_rand = [];
        $users = [];
        $current_date = date('Y-m-d H:i:s');
        if(Yii::$app->user->isGuest){
            $filter = false;
            $userCurrent = 0;
            $user_meet_rand = User::find()
                                ->addSelect(["*", "RAND() order_num"])
                                ->orderBy(['order_num'=> SORT_DESC])
                                ->all();
        }else{
            $userCurrent = Yii::$app->user->id;
            $user_profile = ChatPrivate::find()->where(['user_id'=>$userCurrent, 'post_id'=>$chat_post_id])->one();

            // get list user meet owner
            $list_user_meet_owner = UserMeet::find()
                                        ->addSelect('user_id_1')
                                        ->where('user_id_2 = ' . $userCurrent . ' AND status = 1')
                                        ->all();
            $lmo = [];
            for ($i=0; $i < count($list_user_meet_owner); $i++) { 
                array_push($lmo, $list_user_meet_owner[$i]->user_id_1);
            }

            // get list user which owner meet
            $list_user_owner_meet = UserMeet::find()
                                        ->addSelect('user_id_2')
                                        ->where('user_id_1 = ' . $userCurrent . ' AND status = 1')
                                        ->all();
            $luom = [];
            for ($i=0; $i < count($list_user_owner_meet); $i++) {
                if($list_user_owner_meet[$i]->user_id_2 != $userCurrent && $list_user_owner_meet[$i]->user_id_2 != $user_profile->user_id_guest){
                    array_push($luom, $list_user_owner_meet[$i]->user_id_2);
                }
            }

            // remove user is met owner
            $r = [];
            for ($i=0; $i < count($luom); $i++) { 
                if(($key = array_search($luom[$i], $lmo)) !== false){
                    array_push($r, $lmo[$key]);
                    unset($lmo[$key]);
                }
            }

            $list_met = $userCurrent;
            if(count($lmo) > 0){
                $list_meet_owner = implode(',', $lmo);
                $list_met .= ',' . $list_meet_owner;
                // get rand user meet owner
                $user_meet_rand = User::find()
                            ->addSelect(["*", "RAND() order_num"])
                            ->where('id in ('.$list_meet_owner. ')')
                            ->with('profile')
                            ->orderBy(['order_num'=> SORT_DESC])
                            ->all();
            }
            if(count($luom) > 0){
                $list_owner_meet = implode(',', $luom);
                // $list_met .= ',' . $list_owner_meet;
            }

            $l = $userCurrent;
            if(count($r) > 0){
                $lst = implode(',', $r);
                $l.= ',' . $lst;
            }

            $l .= ',' . $user_profile->user_id_guest;

            // get rand other
            $users = User::find()
                            ->addSelect(["*", "RAND() order_num"])
                            ->where('id NOT IN ('.$l.')')
                            ->with('profile')
                            ->orderBy(['order_num'=> SORT_DESC])
                            ->all();

            $userLogin = User::find()->where('id ='.$userCurrent)->with('profile','setting')->one();
            // collect meet user
            for ($i=0; $i < count($users); $i++) { 
                array_push($user_meet_rand, $users[$i]);
            }
            $tmp; $index;
            for ($i=0; $i < count($user_meet_rand) - 1; $i++) { 
                $index = $i + 2;
                if($i == count($user_meet_rand) - 2){
                    $index = 0;
                }
                if($user_meet_rand[$i]->id == $user_meet_rand[$i + 1]->id){
                    $tmp = $user_meet_rand[$i + 1];
                    $user_meet_rand[$i + 1] = $user_meet_rand[$index];
                    $user_meet_rand[$index] = $tmp;
                }
            }

            if($userLogin->setting){
                $filter = true;
            }else{
                $filter = false;
            }
        }

        $data = [];
        $newdata = [];
        foreach ($user_meet_rand as $key => $value) {

            $posts = Post::find()->where('user_id ='.$value->id)->andWhere(['not',['topic_id'=> NULL]])->orderBy(['created_at'=> SORT_DESC])->limit(4)->all();
            $post_data = [];

            foreach ($posts as $key => $post){
                $item_post = array(
                    'id'=> $post->id,
                    'title'=> '#'.$post->title
                );
                array_push($post_data,$item_post);
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

            $count_like = 0;
            $count_posts = Post::find()->where('user_id ='.$value->id)->all();
            foreach ($count_posts as $key => $post) {
                $vote = Vote::find()->where('post_id = ' . $post->id . ' AND status = 1')->count();
                if($vote>0){
                    $count_like += $vote;
                }
            }
            $brilliant = $count_like;

            $user = array(
                'user_id' => $value->id,
                'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                'met' => $meet,
                'distance'=> $distance,
                'information'=> array(
                    'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                    'image'=> $image,
                    'year_old'=> $year_old,
                    'work'=> $value->profile->work,
                    'about'=> $value->profile->about,
                    'post'=> $post_data,
                    'brilliant'=>$brilliant,
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
                if($value->id == $user_profile->user_id_guest){
                    array_push($newdata, $user);
                }else{
                    array_push($data,$user);
                }
            }elseif (!$filter){
               if($value->id == $user_profile->user_id_guest){
                    array_push($newdata, $user);
                }else{
                    array_push($data,$user);
                }
            }
        }

        for ($i=0; $i < count($data); $i++) {
            array_push($newdata, $data[$i]);
        }

        $temp = array ('data'=> $newdata);
        $hash = json_encode($temp);
        return $hash;
    }

    public function actionGetUserMeetProfileDiscussion(){
        $user_meet_rand = [];
        $users = [];
        $current_date = date('Y-m-d H:i:s');
        $user_is_viewed = $_POST['user_view'];

        if(Yii::$app->user->isGuest){
            $filter = false;
            $userCurrent = 0;
            $user_meet_rand = User::find()
                                ->addSelect(["*", "RAND() order_num"])
                                ->orderBy(['order_num'=> SORT_DESC])
                                ->all();
        }else{
            $userCurrent = Yii::$app->user->id;


            // get list user meet owner
            $list_user_meet_owner = UserMeet::find()
                                        ->addSelect('user_id_1')
                                        ->where('user_id_2 = ' . $userCurrent . ' AND status = 1')
                                        ->all();
            $lmo = [];
            for ($i=0; $i < count($list_user_meet_owner); $i++) {
                array_push($lmo, $list_user_meet_owner[$i]->user_id_1);
            }


            // get list user which owner meet
            $list_user_owner_meet = UserMeet::find()
                                        ->addSelect('user_id_2')
                                        ->where('user_id_1 = ' . $userCurrent . ' AND status = 1')
                                        ->all();
            $luom = [];
            for ($i=0; $i < count($list_user_owner_meet); $i++) {
                if($list_user_owner_meet[$i]->user_id_2 != $userCurrent && $list_user_owner_meet[$i]->user_id_2 != $user_is_viewed){
                    array_push($luom, $list_user_owner_meet[$i]->user_id_2);
                }
            }

            // remove user is met owner
            $r = [];
            for ($i=0; $i < count($luom); $i++) {
                if(($key = array_search($luom[$i], $lmo)) !== false){
                    array_push($r,$lmo[$key]);
                    unset($lmo[$key]);
                }
            }

            $list_met = $userCurrent;
            if(count($lmo) > 0){
                $list_meet_owner = implode(',', $lmo);
                $list_met .= ',' . $list_meet_owner;
                // get rand user meet owner
                $user_meet_rand = User::find()
                            ->addSelect(["*", "RAND() order_num"])
                            ->where('id in ('.$list_meet_owner. ')')
                            ->with('profile')
                            ->orderBy(['order_num'=> SORT_DESC])
                            ->all();
            }
            if(count($luom) > 0){
                $list_owner_meet = implode(',', $luom);
                // $list_met .= ',' . $list_owner_meet;
            }

            $l = $userCurrent;
            if(count($r) > 0){
                $lst = implode(',', $r);
                $l.= ',' . $lst;
            }
            // $l .= ',' . $user_is_viewed;

            // get rand other
            $users = User::find()
                            ->addSelect(["*", "RAND() order_num"])
                            ->where('id NOT IN ('.$l.')')
                            ->with('profile')
                            ->orderBy(['order_num'=> SORT_DESC])
                            ->all();

            $userLogin = User::find()->where('id ='.$userCurrent)->with('profile','setting')->one();

            // collect meet user
            for ($i=0; $i < count($users); $i++) { 
                array_push($user_meet_rand, $users[$i]);
            }
            $tmp; $index;
            for ($i=0; $i < count($user_meet_rand) - 1; $i++) { 
                $index = $i + 2;
                if($i == count($user_meet_rand) - 2){
                    $index = 0;
                }
                if($user_meet_rand[$i]->id == $user_meet_rand[$i + 1]->id){
                    $tmp = $user_meet_rand[$i + 1];
                    $user_meet_rand[$i + 1] = $user_meet_rand[$index];
                    $user_meet_rand[$index] = $tmp;
                }
            }

            if($userLogin->setting){
                $filter = true;
            }else{
                $filter = false;
            }
        }

        $data = [];
        $newdata = [];
        foreach ($user_meet_rand as $key => $value) {

            $posts = Post::find()->where('user_id ='.$value->id)->andWhere(['not',['topic_id'=> NULL]])->orderBy(['created_at'=> SORT_DESC])->limit(4)->all();
            $post_data = [];

            foreach ($posts as $key => $post){
                $item_post = array(
                    'id'=> $post->id,
                    'title'=> '#'.$post->title
                );
                array_push($post_data,$item_post);
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

            $distance = 0;
            if(!Yii::$app->user->isGuest){
                $distance = $this->get_distance($userLogin->profile->lat,$userLogin->profile->lng,$value->profile->lat,$value->profile->lng);
            }

            $count_like = 0;
            $count_posts = Post::find()->where('user_id ='.$value->id)->all();
            foreach ($count_posts as $key => $post) {
                $vote = Vote::find()->where('post_id = ' . $post->id . ' AND status = 1')->count();
                if($vote>0){
                    $count_like += $vote;
                }
            }
            $brilliant = $count_like;

            $user = array(
                'user_id' => $value->id,
                'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                'met' => $meet,
                'distance'=> $distance,
                'information'=> array(
                    'username'=> $value->profile->first_name ." ". $value->profile->last_name,
                    'image'=> $image,
                    'year_old'=> $year_old,
                    'work'=> $value->profile->work,
                    'about'=> $value->profile->about,
                    'post'=> $post_data,
                    'brilliant'=>$brilliant,
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
                if($value->id == $user_is_viewed){
                    array_push($newdata, $user);
                }else{
                    array_push($data,$user);
                }
            }elseif (!$filter){
               if($value->id == $user_is_viewed){
                    array_push($newdata, $user);
                }else{
                    array_push($data,$user);
                }
            }
        }

        for ($i=0; $i < count($data); $i++) { 
            array_push($newdata, $data[$i]);
        }

        $temp = array ('data'=> $newdata);
        $hash = json_encode($temp);
        return $hash;
    }
}

?>