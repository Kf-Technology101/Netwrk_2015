<?php

namespace frontend\modules\netwrk\models\forms;

use Yii;
use yii\base\Model;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\UserKey;

/**
 * LoginForm is the model behind the login form.
 */
class SignupForm extends Model
{
    public $first_name;
    public $last_name;
    public $username;
    public $newPassword;
    public $email;
    public $gender;
    public $zip_code;
    public $dob;

    public function attributeLabels()
    {
        // calculate attribute label for "username"
        // if (Yii::$app->getModule("netwrk")->loginEmail && Yii::$app->getModule("netwrk")->loginUsername) {
        //     $attribute = "Email / Username";
        // } else {
        //     $attribute = Yii::$app->getModule("netwrk")->loginEmail ? "Email" : "Username";
        // }

        return [
            "username" => 'username',
            "newPassword" => "newPassword",
            "email" => "email",
            "gender" => "gender",
        ];
    }
    public function rules()
    {
        return [
            [['username', 'email', 'newPassword', 'gender'], 'required'],
            [['username', 'email', 'newPassword', 'gender'], 'validateSignup']

        ];
    }

    public function validateSignup(){
        $user = new User(["scenario" => "register"]);
        $profile = new Profile();
        $this->addError("username", "username not found");
        // load post data
        $post = Yii::$app->request->post();

        if ($user->load($post)) {

            // ensure profile data gets loaded
            $profile->load($post);

            // validate for ajax request
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user, $profile);
            }

            // validate for normal request
            if ($user->validate()) {

                // perform registration
                $user->setRegisterAttributes(Role::ROLE_USER, Yii::$app->request->userIP)->save(false);
                $profile->setUser($user->id)->save(false);
                $this->afterSignUp($user);
                return $this->goHome();
                // set flash
                // don't use $this->refresh() because user may automatically be logged in and get 403 forbidden
                // $successText = "Successfully registered [{$user->getDisplayName()}]";
                // $guestText = "";
                // if (Yii::$app->user->isGuest) {
                //     $guestText =  " - Please check your email to confirm your account";
                // }
                // Yii::$app->session->setFlash("Register-success", $successText . $guestText);
            }
        }
    }
}