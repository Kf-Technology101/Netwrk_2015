<?php use yii\helpers\Url; ?>
<div id='reset-password'>
    <div class="header">
        <div class="title">
            <img src="<?= Url::to('@web/img/icon/netwrk-logo-blue.png'); ?>">
            <p> Reset your password</p>
        </div>
    </div>
    <div class="container">
        <div class="form-group">
            <label class="title-field">New Password</label>
            <input type="password" class="form-control new-pass" id="new-pass" placeholder="New Password">
        </div>
        <div class="form-group">
            <label class="title-field">Confirm Password</label>
            <input type="password" class="form-control confirm-pass" id="confirm-pass" placeholder="Confirm Password">
        </div>
        <div class="reset disable">Reset</div>
    </div>
</div>