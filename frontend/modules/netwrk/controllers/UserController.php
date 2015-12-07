<?php
namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\modules\netwrk\models\User;
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

        // load post data and login
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login(Yii::$app->getModule("netwrk")->loginDuration)) {
            return $this->goBack(Yii::$app->getModule("netwrk")->loginRedirect);
        }
        // echo "<pre>";print_r($model['_errors']);die;
        // render
        return $this->render($this->getIsMobile() ? 'mobile/login' : 'login',[
        	'model' => $model
        ]);
    }

    public function actionIndex(){
        //return $this->render($this->getIsMobile() ? 'mobile/login' : '');
        // echo "<pre>";print_r(Yii::$app->user);die;
    }

    public function actionSignup(){
        return $this->render($this->getIsMobile() ? 'mobile/signup' : '');
    }

    public function actionRegister()
    {

        $user = new User();
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
            if ($user->validate() && $profile->validate()) {

                // perform registration
                $role = Yii::$app->getModule("netwrk")->model("Role");
                $user->setRegisterAttributes($role::ROLE_USER, Yii::$app->request->userIP)->save(false);
                $profile->setUser($user->id)->save(false);
                $this->afterRegister($user);

                // set flash
                // don't use $this->refresh() because user may automatically be logged in and get 403 forbidden
                $successText = "Successfully registered [$user->getDisplayName()]";
                $guestText = "";
                if (Yii::$app->user->isGuest) {
                    $guestText =  " - Please check your email to confirm your account";
                }
                Yii::$app->session->setFlash("Register-success", $successText . $guestText);
            }
        }

        // render
        return $this->render("signup", [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }
}