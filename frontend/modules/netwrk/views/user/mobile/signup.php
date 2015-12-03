<?php use yii\helpers\Url; ?>
<div id='page-signup'>
    <div class="header">
        <p> Sign Up</p>
    </div>
    <div class="form-register">
        <div class="field-name">
            <input type="text" class="username form-control" maxlength="128" placeholder="Username">
        </div>
        <div class="field-name">
            <input type="text" class="email form-control" maxlength="128" placeholder="Email">
        </div>
        <div class="col-field-name">
            <input type="password" class="password form-control" maxlength="128" placeholder="Password">
        </div>
        <div class="col-field-name sex dropdown input-group">
            <input type="text" class="gender form-control" maxlength="128" placeholder="Gender" data-toggle="dropdown">
            <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li>Male</li>
                <li>Female</li>
            </ul>
        </div>
        <div class="col-field-name">
            <input type="text" class="zipcode form-control" maxlength="128" placeholder="Zipcode">
        </div>
        <div class="col-field-name">
            <input type="text" class="age form-control" maxlength="128" placeholder="Age must be at least 18">
        </div>
    </div>
    <div class="btn-control disable">
        <p>Sign Up</p>
    </div>
    <div class="sign-in">
        <p>Already have an account! <a href="<?= Url::base(true); ?>/netwrk/user/">Login</a> now</p>
    </div>
</div>