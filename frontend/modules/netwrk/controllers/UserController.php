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
            return $this->goBack(Yii::$app->getModule("user")->loginRedirect);
        }

        // render
        return $this->render($this->getIsMobile() ? 'mobile/login' : 'login',[
        	'model' => $model
        ]);
    }

    public function actionIndex(){
        var_dump(Yii::$app->user);
        die;
        //return $this->render($this->getIsMobile() ? 'mobile/login' : '');
    }

    public function actionSignup(){
        return $this->render($this->getIsMobile() ? 'mobile/signup' : '');
    }
}