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
                'about'=> $user->profile->about,
                'year_old' => $year_old
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
        $newPassword = $post['User']['newPassword'];
        $newPasswordConfirm = $post['User']['newPasswordConfirm'];

        $user = new User(["scenario" => "password_setting"]);
        $user = $user::findOne(Yii::$app->user->id);
        $user->setScenario("password_setting");

        $user->currentPassword = $currentPassword;
        $user->newPassword = $newPassword;
        $user->newPasswordConfirm = $newPasswordConfirm;
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
}

?>