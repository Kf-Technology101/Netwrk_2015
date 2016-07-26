<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\netwrk\models\User;
    use frontend\modules\netwrk\models\Profile;
?>
<div id='page-signup'>
    <div class="header">
        <a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo-blue.png'); ?>"></a>
        <p>Sign up</p>
    </div>

    <?php
        $scenario = (isset($scenario)) ? $scenario : 'register';
    echo $scenario;
        $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-register form-horizontal',
        'autocomplete'=> 'off'],
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
        'enableAjaxValidation' => true,
    ]);
    ?>
        <div class="col-field-name">
            <?= $form->field($profile, 'first_name')->textInput(array('placeholder' => 'First name','autofocus'=>true)); ?>
        </div>
        <div class="col-field-name">
            <?= $form->field($profile, 'last_name')->textInput(array('placeholder' => 'Last name')); ?>
        </div>

        <?php if($scenario == 'register') : ?>
            <div class="col-field-name">
                <?= $form->field($user, 'username')->textInput(array('placeholder' => 'Username')) ?>
            </div>
            <div class="col-field-name">
                <?= $form->field($user,'email')->textInput(array('placeholder' => 'Email')); ?>
            </div>
        <?php endif; ?>

        <div class="col-field-name">
            <!-- <input type="password" class="password form-control" maxlength="128" placeholder="Password"> -->
            <?= $form->field($user, 'newPassword')->passwordInput(array('placeholder' => 'Password')); ?>
        </div>

        <div class="col-field-name">
            <?= $form->field(
                    $profile,
                    'gender',
                    [
                       'template'=>"<div class=\"col-lg-3 input-group sex\">{input}\n
                       <span class='input-group-addon' data-toggle='dropdown'><i class='fa fa-sort'></i></span>\n
            <ul class='dropdown-menu' aria-labelledby='dropdownMenu2'><li>Male</li><li>Female</li></ul></div>\n<div class=\"col-lg-7\">{error}</div>"
                    ])->textInput(array('placeholder' => 'Gender',"data-toggle"=>'dropdown','class'=>'form-control dropdown','readonly'=>true)); ?>
        </div>
        <div class="col-field-name zipcode">
            <?= $form->field($profile, 'zip_code')->textInput(array('placeholder' => '46140','maxlength'=>5)); ?>
        </div>
        <!--<div class="col-field-name age">
            <?/*= $form->field($profile, 'dob')->textInput(array('placeholder' => 'Age must be at least 18')); */?>
        </div>-->

        <?php
            $years_to = date('Y') - 17;
            $years_from = $years_to - 100;

            $day = array_combine(range(1,31),range(1,31));
            $months = array_combine(range(1,12),range(1,12));
            $years = array_combine(range($years_from,$years_to), range($years_from,$years_to));
        ?>
        <div class="dob-field-name dob field">
            <?= $form->field($profile, 'day')->dropDownList($day,['prompt' => 'Day']); ?>
        </div>
        <div class="dob-field-name dob field">
            <?= $form->field($profile, 'month')->dropDownList($months,['prompt' => 'Month']); ?>
        </div>
        <div class="dob-field-name dob year field">
            <!--Make year 1985 as default selected -->
            <?= $form->field($profile, 'year')->dropDownList($years,
                ['options' => [
                    1985 => [
                        'Selected' => 'selected'
                    ]
                ]]
            ); ?>
        </div>

        <div class="col-field-name" style="display:none;">
            <?=  $form->field($profile, 'lat')->hiddenInput()->label(false); ?>
            <?=  $form->field($profile, 'lng')->hiddenInput()->label(false); ?>
        </div>
        <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary btn-control']) ?>
        <!-- <div class="btn-control sign-up">
            <p>Sign Up</p>
        </div> -->
        <div class="sign-in">
        <?php if($url && $url != Url::base(true)){?>
             <p>Already have an account! <a href="<?= Url::base(true); ?>/netwrk/user/login?url_callback=<?=$url?>">Log in</a> Now</p>
        <?php }else{ ?>
            <p>Already have an account! <a href="<?= Url::base(true); ?>/netwrk/user/login">Log in</a> Now</p>
        <?php } ?>
        </div>
    <?php ActiveForm::end(); ?>


</div>