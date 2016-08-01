<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\authclient\widgets\AuthChoice;
    use frontend\modules\netwrk\models\forms\LoginForm;
?>
<div class="modal" id='login' role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="text-center">Log In With Your Social Account</p>
            </div>
            <div class="modal-body">
                <div class="row social-login-wrapper">
                    <div class="col-lg-12 text-center">
                        <?php $authAuthChoice = AuthChoice::begin(['baseAuthUrl' => ['user/auth'], 'autoRender' => false]); ?>
                            <ul>
                                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                                    <li class="social-item">
                                        <?= Html::a( Html::beginTag('i',['class' => "fa fa-$client->name"]).Html::endTag('i').$client->title, ['user/auth', 'authclient'=> $client->name, ], ['class' => "btn btn-block btn-default $client->name "]) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php AuthChoice::end(); ?>
                    </div>
                </div>
                <hr class="or-block">
                <span class="or-text">Or</span>
                <hr class="or-block">
                <?php
                $model = new LoginForm();
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'form-login form-horizontal'],
                    'fieldConfig' => [
                        'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                        'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],

                ]); ?>
                    <div class="field-name username">
                        <p class="title"> Username </p>
                        <?= $form->field($model, 'username')->textInput(array('placeholder' => 'Username','autofocus'=>true)); ?>
                    </div>
                    <div class="field-name password">
                        <p class="title"> Password </p>
                        <a href="javascript:void(0)" class="forgot-password">Forgot password</a>
                        <?= $form->field($model, 'password')->passwordInput(array('placeholder' => 'Password')); ?>
                    </div>
                <div class="btn-control">
                    <p>Login</p>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="sign-up">
                    <p>Don't have an account! <b>Sign up</b> Now</p>
                </div>
            </div>
        </div>
    </div>
</div>
