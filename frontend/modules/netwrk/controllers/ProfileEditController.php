<?php
namespace frontend\modules\netwrk\controllers;

use Yii;
use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Profile;
use yii\helpers\Url;

class ProfileEditController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('mobile/index');
    }
    public function actionSocialSignup()
    {
        $currentUser = Yii::$app->user->id;
        $user = User::find()->where('id ='.$currentUser)->with('profile')->one();
        $profile = $user->profile;
        $profile->day = '1';
        $profile->month = '1';

        // render
        return $this->render($this->getIsMobile() ? 'mobile/social_signup_profile_info' : $this->goHome(), [
            'user'    => $user,
            'profile' => $profile
        ]);
    }
}

?>