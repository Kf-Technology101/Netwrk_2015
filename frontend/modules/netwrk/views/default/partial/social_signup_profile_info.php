<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\netwrk\controllers\UserController;
    use frontend\modules\netwrk\models\User;
    use frontend\modules\netwrk\models\Profile;
?>
<div class="modal" id='social_signup_profile_info' role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p>Profile details</p>
            </div>
            <div class="modal-body">
                <?php
                    $scenario = 'social_signup';
                    $session = Yii::$app->session;

                    $form = ActiveForm::begin([
                        'id' => 'social-register-form',
                        'options' => ['class' => 'form-register form-horizontal'],
                        'fieldConfig' => [
                            'template' => "<div class=\"col-md-12\">{input}</div>\n<div class=\"col-md-12\">{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]);
                    $user = new User(["scenario" => $scenario]);
                    $profile = new Profile();
                ?>
                    <div class="col-field-name field">
                        <?= $form->field($profile, 'first_name')->textInput(array('placeholder' => 'First Name','autofocus'=>true)); ?>
                    </div>
                    <div class="col-field-name field">
                        <?= $form->field($profile, 'last_name')->textInput(array('placeholder' => 'Last Name')); ?>
                    </div>

                    <?php if($scenario == 'register') : ?>
                        <div class="field-name field">
                            <?= $form->field($user,'email')->textInput(array('placeholder' => 'Email','autocomplete'=> 'off')); ?>
                        </div>
                    <?php endif;?>

                    <?php
                        $genders = array('Male' => 'Male', 'Female' => 'Female');
                    ?>
                    <div class="col-field-name field">
                        <?= $form->field($profile, 'gender')->dropDownList($genders,['prompt' => 'Gender']); ?>
                    </div>

                    <div class="col-field-name zip field">
                        <?= $form->field($profile, 'zip_code')->textInput(array('placeholder' => '46140','maxlength'=>5)); ?>
                    </div>

                    <?php
                        $years_to = date('Y') - 17;
                        $years_from = $years_to - 100;

                        $day = array_combine(range(1,31),range(1,31));
                        $months = array_combine(range(1,12),range(1,12));
                        $years = array_combine(range($years_from,$years_to), range($years_from,$years_to));
                    ?>
                    <div>
                        <label class="control-lable">Birthday</label>
                    </div>
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
                <?=  $form->field($profile, 'lat')->hiddenInput()->label(false); ?>
                <?=  $form->field($profile, 'lng')->hiddenInput()->label(false); ?>
                <div class="btn-control">
                    <p>Update & Continue</p>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>