<?php
namespace frontend\modules\netwrk\controllers;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use yii\helpers\Url;
use Yii;

use yii\widgets\ActiveForm;

class ProfileController extends BaseController
{
    private $currentUser = 1;
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(array('/netwrk/user/login'));
        }
        return $this->render('mobile/index');
    }

    public function actionGetProfile()
    {
        $currentUser = Yii::$app->user->id;
        $current_date = date('Y-m-d H:i:s');
        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();

        if($user && $user->profile){

            $years = $user->profile->age;
            // $time1 = date_create($years);
            // $time2 = date_create($current_date);
            // $year_old = $time1->diff($time2)->y;

            if ($user->profile->photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                //get avatar
                $image = Url::to('@web/uploads/'.$currentUser.'/'.$user->profile->photo);
            }

            $birthday = new \DateTime($user->profile->dob);
            $birthday = $birthday->format('Y-m-d');

            $current_date = date('Y-m-d H:i:s');
            $time1 = date_create($user->profile->dob);
            $time2 = date_create($current_date);
            $year_old = $time1->diff($time2)->y;

            $info = array(
                'status' => 1,
                'username'=> $user->profile->first_name ." ". $user->profile->last_name,
                'age'=> $birthday,
                'work'=> $user->profile->work,
                'image' => $image,
                'zip'=> $user->profile->zip_code,
                'country' => $user->profile->country,
                'state' => $user->profile->state,
                'city' => $user->profile->user_city,
                'about'=> $user->profile->about,
                'year_old' => $year_old,
                'meet_info' => $user->profile->meet_info
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
        $currentUser = Yii::$app->user->id;
        $status = 0;

        $age = $_POST['age'];
        $work = $_POST['work'];
        $about = $_POST['about'];
        $zipcode = $_POST['zipcode'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();
        $profile = $user->profile;

        $user->profile->dob = $age;
        $user->profile->work = $work;
        $user->profile->about = $about;
        $user->profile->zip_code = $zipcode;
        $user->profile->lat = $lat;
        $user->profile->lng = $lng;

        $user->profile->update();
        $birthday = new \DateTime($user->profile->dob);
        $birthday = $birthday->format('Y-m-d');

        if ($user->profile->photo == null){
            $image = Url::to('@web/img/icon/no_avatar.jpg');
        }else{
            //get avatar
            $image = Url::to('@web/uploads/'.$currentUser.'/'.$user->profile->photo);
        }

        $data = array(
            'status' => 1,
            'username'=> $user->profile->first_name ." ". $user->profile->last_name,
            'age'=> $birthday,
            'work'=> $user->profile->work,
            'image' => $image,
            'zip'=> $user->profile->zip_code,
            'about'=> $user->profile->about,
        );
        $hash = json_encode($data);
        return $hash;
    }

    public function actionUploadImage()
    {
        $currentUser = Yii::$app->user->id;

        $image = $_FILES[ 'image' ];

        if (!isset($_FILES[ 'image' ])) {

            return;
        }

        $postdata = fopen( $_FILES[ 'image' ][ 'tmp_name' ], "r" );
        //  Get file extension
        $extension = substr( $_FILES[ 'image' ][ 'name' ], strrpos( $_FILES[ 'image' ][ 'name' ], '.' ) );

        // Generate unique name /
        $filename = $currentUser . '-' . time() . $extension;

        $upload_path = \Yii::getAlias('@frontend') . "/web/uploads/".$currentUser."/";
        if (!is_dir($upload_path)) {
            mkdir( $upload_path, 0777, true);
        }
        // Open a file for writing /
        $fp = fopen( $upload_path . $filename, "w" );

        /* Read the data 1 KB at a time
          and write to the file */
        while( $data = fread( $postdata, 1024 ) )
            fwrite( $fp, $data );

        // Close the streams /
        fclose( $fp );
        fclose( $postdata );

        $user = User::find()->where('id ='.$currentUser)->one();
        $user->profile->photo = $filename;
        $user->profile->update();

        $image = Url::to('@web/uploads/'.$currentUser.'/'.$user->profile->photo);
        $hash = json_encode(array('data_image'=>$image));
        return $hash;
    }

    public function actionPasswordSetting()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $currentPassword = $post['User']['currentPassword'];
        $newPasswordProfile = $post['User']['newPasswordProfile'];
        $newPasswordConfirmProfile = $post['User']['newPasswordConfirmProfile'];

        $user = new User(["scenario" => "password_setting"]);
        $user = $user::findOne(Yii::$app->user->id);
        $user->setScenario("password_setting");

        $user->currentPassword = $currentPassword;
        $user->newPasswordProfile = $newPasswordProfile;
        $user->newPasswordConfirmProfile = $newPasswordConfirmProfile;
        // load post data and reset user password

        $form = ActiveForm::validate($user);

        if ($user->validate() && $user->save()) {
            $data = ['status'=> 1,'data'=> 'Password updated successfully'];
        } else {
            $data = ['status'=> 0,'data'=> $form];
        }

        $hash = json_encode($data);
        return $hash;
    }

    public function actionGetProfileBasicInfo()
    {
        $currentUser = Yii::$app->user->id;
        $current_date = date('Y-m-d H:i:s');
        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();

        if($user && $user->profile){
            $birthday = new \DateTime($user->profile->dob);
            $birthday = $birthday->format('Y-m-d');

            $info = array(
                'status' => 1,
                'first_name' => $user->profile->first_name,
                'last_name' => $user->profile->last_name,
                'user_name' => $user->username,
                'email' => $user->email,
                'gender' => $user->profile->gender,
                'zip'=> $user->profile->zip_code,
                'dob'=> $birthday,
                'marital_status' => $user->profile->marital_status,
                'work'=> $user->profile->work,
                'education' => $user->profile->education,
                'country' => $user->profile->country,
                'state' => $user->profile->state,
                'city' => $user->profile->user_city,
                'hobbies' => $user->profile->hobbies,
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

    public function actionUpdateProfileEdit()
    {
        $currentUser = Yii::$app->user->id;
        $status = 0;

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $zip = $_POST['zip'];
        $dob = $_POST['dob'];
        $marital_status = $_POST['marital_status'];
        $work = $_POST['work'];
        $education = $_POST['education'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $hobbies = $_POST['hobbies'];
        $about = $_POST['about'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $meet_info = $_POST['meet_info'];

        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();
        $profile = $user->profile;

        $user->profile->first_name = $first_name;
        $user->profile->last_name = $last_name;
        $user->profile->gender = $gender;
        $user->profile->zip_code = $zip;
        $user->profile->dob = $dob;
        $user->profile->marital_status = $marital_status;
        $user->profile->work = $work;
        $user->profile->education = $education;
        $user->profile->country = $country;
        $user->profile->state = $state;
        $user->profile->user_city = $city;
        $user->profile->hobbies = $hobbies;
        $user->profile->about = $about;
        $user->profile->lat = $lat;
        $user->profile->lng = $lng;

        $user->profile->update();
        $birthday = new \DateTime($user->profile->dob);
        $birthday = $birthday->format('Y-m-d');

        $data = array(
            'status' => 1,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'user_name' => $user->username,
            'email' => $user->email,
            'gender' => $user->profile->gender,
            'zip'=> $user->profile->zip_code,
            'dob'=> $birthday,
            'marital_status' => $user->profile->marital_status,
            'work'=> $user->profile->work,
            'education' => $user->profile->education,
            'country' => $user->profile->country,
            'state' => $user->profile->state,
            'city' => $user->profile->user_city,
            'hobbies' => $user->profile->hobbies,
            'about'=> $user->profile->about,
        );
        $hash = json_encode($data);
        return $hash;
    }

    public function actionUpdateSocialProfileInfo()
    {
        $currentUser = Yii::$app->user->id;

        $post = Yii::$app->request->post();

        $first_name = $post['Profile']['first_name'];
        $last_name = $post['Profile']['last_name'];
        $gender = $post['Profile']['gender'];
        $zip = $post['Profile']['zip_code'];
        $year = $post['Profile']['year'];
        $month = $post['Profile']['month'];
        $day = $post['Profile']['day'];
        $lat = $post['Profile']['lat'];
        $lng = $post['Profile']['lng'];
        $dob = $year .'-'. $month .'-'. $day;

        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();
        $profile = $user->profile;

        $user->profile->first_name = $first_name;
        $user->profile->last_name = $last_name;
        $user->profile->gender = $gender;
        $user->profile->zip_code = $zip;
        $user->profile->dob = $dob;
        $user->profile->lat = $lat;
        $user->profile->lng = $lng;

        $user->profile->update();
        $birthday = new \DateTime($user->profile->dob);
        $birthday = $birthday->format('Y-m-d');

        $data = array(
            'status' => 1,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'user_name' => $user->username,
            'email' => $user->email,
            'gender' => $user->profile->gender,
            'zip'=> $user->profile->zip_code,
            'dob'=> $birthday
        );
        $hash = json_encode($data);
        return $hash;
    }

    public function actionUpdateProfileMeetInfo()
    {
        $currentUser = Yii::$app->user->id;
        $meet_info = $_POST['meet_info'];
        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();
        $profile = $user->profile;
        $user->profile->meet_info = $meet_info;
        $user->profile->update();
        $data = array(
            'status' => 1,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'user_name' => $user->username,
            'email' => $user->email,
            'gender' => $user->profile->gender,
            'zip'=> $user->profile->zip_code,
            'marital_status' => $user->profile->marital_status,
            'work'=> $user->profile->work,
            'education' => $user->profile->education,
            'country' => $user->profile->country,
            'state' => $user->profile->state,
            'city' => $user->profile->user_city,
            'hobbies' => $user->profile->hobbies,
            'about'=> $user->profile->about,
            'meet_info'=> $user->profile->meet_info,
        );
        $hash = json_encode($data);
        return $hash;
    }
}

?>