<?php use yii\helpers\Url; ?>
<div id='forgot-password'>
    <div class="header">
        <div class="title">
            <img src="<?= Url::to('@web/img/icon/netwrk-logo-blue.png'); ?>">
            <p> Forgot your password</p>
        </div>
    </div>
    <div class="container">
        <p class="description">Please enter the email address associated and we will send you an email with a link to reset your password</p>
        <input type="text" class="form-control email" name="email" placeholder="Email address">
        <div class="send-email disable">Send Email</div>
    </div>
</div>