<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\netwrk\controllers\UserController;
    use frontend\modules\netwrk\models\User;
    use frontend\modules\netwrk\models\Profile;
?>
<div class="modal" id='signup' role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p> Sign up </p>
            </div>
            <div class="modal-body">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'register-form',
                        'options' => ['class' => 'form-register form-horizontal'],
                        'fieldConfig' => [
                            'template' => "<div class=\"col-md-12\">{input}</div>\n<div class=\"col-md-12\">{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]);
                    $user = new User(["scenario" => "register"]);
                    $profile = new Profile();

                    // $post = Yii::$app->request->post();

                    // if ($user->load($post)) {
                        // $user->validate();
                        // $profile->validate();
                    // }
                ?>
                    <div class="col-field-name field">
                        <?= $form->field($profile, 'first_name')->textInput(array('placeholder' => 'First Name')); ?>
                    </div>
                    <div class="col-field-name field">
                        <?= $form->field($profile, 'last_name')->textInput(array('placeholder' => 'Last Name')); ?>
                    </div>
                    <div class="field-name field">
                        <?= $form->field($user, 'username')->textInput(array('placeholder' => 'Username')) ?>
                    </div>
                    <div class="field-name field">
                        <?= $form->field($user,'email')->textInput(array('placeholder' => 'Email','autocomplete'=> 'off')); ?>
                    </div>
                    <div class="col-field-name field">
                        <?= $form->field($user, 'newPassword')->passwordInput(array('placeholder' => 'Password')); ?>
                    </div>
                    <div class="col-field-name sex field">
                        <?= $form->field(
                                $profile,
                                'gender',
                                [
                                   'template'=>"<div class=\"col-md-12 input-group sex\">{input}\n
                                   <span class='input-group-addon' data-toggle='dropdown'><i class='fa fa-sort'></i></span>\n
                        <ul class='dropdown-menu' aria-labelledby='dropdownMenu2'><li>Male</li><li>Female</li></ul></div>\n<div class=\"col-md-12\">{error}</div>"
                                ])->textInput(array('placeholder' => 'Gender',"data-toggle"=>'dropdown','class'=>'form-control dropdown','readonly'=>true)); ?>
                    </div>
                    <div class="col-field-name zipcode field">
                        <?= $form->field($profile, 'zip_code')->textInput(array('placeholder' => 'Zip Code','maxlength'=>5)); ?>
                    </div>
                    <div class="col-field-name age field">
                        <?= $form->field($profile, 'dob')->textInput(array('placeholder' => 'Age must be at least 18')); ?>
                    </div>
                    <?=  $form->field($profile, 'lat')->hiddenInput()->label(false); ?>
                    <?=  $form->field($profile, 'lng')->hiddenInput()->label(false); ?>
                <div class="btn-control">
                    <p>Sign Up</p>
                </div>
            <?php ActiveForm::end(); ?>
                <div class="sign-in">
                    <p>Already have an account! <b>Log in</b> Now</p>
                </div>
            </div>
        </div>
    </div>
</div>