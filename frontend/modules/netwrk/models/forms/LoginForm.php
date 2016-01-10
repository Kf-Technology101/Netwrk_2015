<?php

namespace frontend\modules\netwrk\models\forms;

use Yii;
use yii\base\Model;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\UserKey;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    /**
     * @var string Username and/or email
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var bool If true, users will be logged in for $loginDuration
     */
    public $rememberMe = true;

    /**
     * @var \amnah\yii2\user\models\User
     */
    protected $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [["username", "password"], "required"],
            ["username", "validateUser"],
            ["username", "validateUserStatus"],
            ["password", "validatePassword"],
            ["rememberMe", "boolean"],
        ];
    }

    /**
     * Validate user
     */
    public function validateUser()
    {
        // check for valid user or if user registered using social auth
        $user = $this->getUser();
        if (!$user || !$user->password) {
            if (Yii::$app->getModule("netwrk")->loginEmail && Yii::$app->getModule("netwrk")->loginUsername) {
                $attribute = "Email / Username";
            } else {
                $attribute = Yii::$app->getModule("user")->loginEmail ? "Email" : "Username";
            }
            $this->addError("username", "$attribute not found");

            // do we need to check $user->userAuths ???
        }
    }

    /**
     * Validate user status
     */
    public function validateUserStatus()
    {
        // check for ban status
        $user = $this->getUser();
        if ($user->ban_time) {
            $this->addError("username", "User is banned". $user->ban_reason );
        }

        // check status and resend email if inactive
        if ($user->status == $user::STATUS_INACTIVE) {

            /** @var \amnah\yii2\user\models\UserKey $userKey */
            // $userKey = Yii::$app->getModule("netwrk")->model("UserKey");
            // $userKey = UserKey::generate($user->id, UserKey::TYPE_EMAIL_ACTIVATE);
            // $user->sendEmailConfirmation($userKey);
            // $this->addError("username", "Confirmation email resent");
        }
    }

    /**
     * Validate password
     */
    public function validatePassword()
    {
        // skip if there are already errors
        if ($this->hasErrors()) {
            return;
        }

        /** @var \amnah\yii2\user\models\User $user */

        // check if password is correct
        $user = $this->getUser();
        if (!$user->validatePassword($this->password)) {
            $this->addError("password", "Incorrect password");
        }
    }

    /**
     * Get user based on email and/or username
     *
     * @return \amnah\yii2\user\models\User|null
     */
    public function getUser()
    {
        // check if we need to get user
        if ($this->_user === false) {

            // build query based on email and/or username login properties
            // $user = Yii::$app->getModule("user")->model("User");
            $user = User::find();
            if (Yii::$app->getModule("netwrk")->loginEmail) {
                $user->orWhere(["email" => $this->username]);
            }
            if (Yii::$app->getModule("netwrk")->loginUsername) {
                $user->orWhere(["username" => $this->username]);
            }

            // get and store user
            $this->_user = $user->one();
        }

        // return stored user
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        // calculate attribute label for "username"
        if (Yii::$app->getModule("netwrk")->loginEmail && Yii::$app->getModule("netwrk")->loginUsername) {
            $attribute = "Email / Username";
        } else {
            $attribute = Yii::$app->getModule("netwrk")->loginEmail ? "Email" : "Username";
        }

        return [
            "username" => $attribute,
            "password" => "Password",
            "rememberMe" => "Remember Me",
        ];
    }

    /**
     * Validate and log user in
     *
     * @param int $loginDuration
     * @return bool
     */
    public function login($loginDuration)
    {
        if ($this->validate()) {
            // echo "<pre>";print_r(Yii::$app->user->login($this->getUser(), $this->rememberMe ? $loginDuration : 0));die;
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $loginDuration : 0);
        }

        return false;
    }
}