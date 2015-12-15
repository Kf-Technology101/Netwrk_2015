<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div id='page-login'>

    <div class="header">
        <a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo-blue.png'); ?>"></a>
        <p> Log in</p>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-login form-horizontal',
                        'autocomplete'=> 'on'
                     ],
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],

    ]); ?>
    <div class="field-name">
        <p class="title"> Username </p>
        <!-- <input type="text" class="username form-control" maxlength="128" placeholder="Username"> -->
        <?= $form->field($model, 'username')->textInput(array('placeholder' => 'Username')); ?>
    </div>
    <div class="field-name password">
        <p class="title"> Password </p>
        <a href="<?= Url::base(true); ?>/netwrk/user/forgot-password" class="forgot-password">Forgot password</a>
        <!-- <input type="password" class="password form-control" maxlength="128" placeholder="Password"> -->
        <?= $form->field($model, 'password')->passwordInput(array('placeholder' => 'Password')); ?>
    </div>
    <div class="btn-control">
        <p>Login</p>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="sign-up">
        <p>Don't have an account! <a href="<?= Url::base(true); ?>/netwrk/user/signup">Sign up</a> Now</p>
    </div>
</div>