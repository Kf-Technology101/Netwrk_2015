<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\netwrk\models\forms\LoginForm;
?>
<div class="modal" id='login' role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="header">
                    <p>Log in</p>
                </div>
            </div>
            <div class="modal-body">
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
                        <?= $form->field($model, 'username')->textInput(array('placeholder' => 'Username')); ?>
                    </div>
                    <div class="field-name password">
                        <p class="title"> Password </p>
                        <?= $form->field($model, 'password')->passwordInput(array('placeholder' => 'Password')); ?>
                    </div>
                    <div class="field-name">
                        <a href="javascript:void(0)" class="forgot-password">Forgot password</a>
                    </div>
                <div class="btn-control">
                    <p>Login</p>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="sign-up">
                    <p>Don't have an account! <b>Sign Up</b> now</p>
                </div>
            </div>
        </div>
    </div>
</div>
