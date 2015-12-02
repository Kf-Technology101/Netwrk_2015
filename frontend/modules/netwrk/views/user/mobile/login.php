<?php use yii\helpers\Url; ?>
<div id='page-login'>

    <div class="header">
        <p> Log in</p>
    </div>

    <div class="form-login">
        <div class="field-name">
            <p class="title"> Username </p>
            <input type="text" class="username form-control" maxlength="128" placeholder="Username">
        </div>
        <div class="field-name">
            <p class="title"> Password </p>
            <input type="password" class="password form-control" maxlength="128" placeholder="Password">
        </div>
        <div class="field-name">
            <a href="javascript:void(0)" class="forgot-password">Forgot password</a>
        </div>
    </div>
    <div class="btn-control disable">
        <p>Login</p>
    </div>
    <div class="sign-up">
        <p>Don't have an account! <a href="javascript:void(0)">Sign Up</a> now</p>
    </div>
</div>