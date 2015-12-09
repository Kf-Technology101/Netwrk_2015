<?php
namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\UserKey;
use frontend\modules\netwrk\models\Role;
use frontend\modules\netwrk\models\Profile;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\UserMeet;
use frontend\modules\netwrk\models\UserSettings;

use frontend\modules\netwrk\models\forms\LoginForm;

use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\db\ActiveQuery;

use yii\helpers\Url;

class UserController extends BaseController
{

    /**
     * Display login page
     */
    public function actionLogin()
    {
        /** @var \amnah\yii2\user\models\forms\LoginForm $model */
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        // echo "<pre>";print_r(Yii::$app->user); die;
        // load post data and login
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login(Yii::$app->getModule("netwrk")->loginDuration)) {
            // echo "<pre>";print_r(Yii::$app->user);die;
            // return $this->goBack(Yii::$app->getModule("netwrk")->loginRedirect);
            return $this->goHome();
        }

        return $this->render($this->getIsMobile() ? 'mobile/login' : 'login',[
        	'model' => $model
        ]);
    }

    public function actionLoginUser(){
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login(Yii::$app->getModule("netwrk")->loginDuration)) {
            $data = array('status' => 1,'data'=>Yii::$app->user->isGuest);
        }else{
            $data = array('status' => 0,'data'=>$model['_errors']);
        }
        $hash = json_encode($data);
        return $hash;
    }

    public function actionIndex(){
        //return $this->render($this->getIsMobile() ? 'mobile/login' : '');
        // echo "<pre>";print_r(Yii::$app->user);die;
    }

    public function actionSignup()
    {

        $user = new User(["scenario" => "register"]);
        $profile = new Profile();

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

                // set flash
                // don't use $this->refresh() because user may automatically be logged in and get 403 forbidden
                $successText = "Successfully registered [{$user->getDisplayName()}]";
                $guestText = "";
                if (Yii::$app->user->isGuest) {
                    $guestText =  " - Please check your email to confirm your account";
                }
                Yii::$app->session->setFlash("Register-success", $successText . $guestText);
            }
        }

        // render
        return $this->render($this->getIsMobile() ? 'mobile/signup' : $this->goHome(), [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }

    protected function afterSignUp($user)
    {
        /** @var \amnah\yii2\user\models\UserKey $userKey */

        // determine userKey type to see if we need to send email
        if ($user->status == $user::STATUS_INACTIVE) {
            $userKeyType = UserKey::TYPE_EMAIL_ACTIVATE;
        } elseif ($user->status == $user::STATUS_UNCONFIRMED_EMAIL) {
            $userKeyType = UserKey::TYPE_EMAIL_CHANGE;
        } else {
            $userKeyType = null;
        }

        // check if we have a userKey type to process, or just log user in directly
        if ($userKeyType) {

            // generate userKey and send email
            $userKey = UserKey::generate($user->id, $userKeyType);
            if (!$numSent = $user->sendEmailConfirmation($userKey)) {

                // handle email error
                Yii::$app->session->setFlash("Email-error", "Failed to send email");
            }
        } else {
            Yii::$app->user->login($user, Yii::$app->getModule("netwrk")->loginDuration);
        }
    }
}