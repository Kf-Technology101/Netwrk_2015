<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\netwrk\models\User;
    use frontend\modules\netwrk\models\Profile;
?>
<div id='social-page-signup'>
    <div class="header">
        <p>Profile details</p>
    </div>

    <?php
        $scenario = (isset($scenario)) ? $scenario : 'social_signup';
        $form = ActiveForm::begin([
        'id' => 'social-register-form',
        'options' => ['class' => 'social-form-register form-horizontal',
        'autocomplete'=> 'off'],
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ]
    ]);
    ?>
        <div class="col-field-name">
            <?= $form->field($profile, 'first_name')->textInput(array('placeholder' => 'First name','autofocus'=>true)); ?>
        </div>
        <div class="col-field-name">
            <?= $form->field($profile, 'last_name')->textInput(array('placeholder' => 'Last name')); ?>
        </div>

        <div class="col-field-name">
            <?php
            $genders = array('Male' => 'Male', 'Female' => 'Female');
            ?>
            <div class="col-field-name field">
                <?= $form->field($profile, 'gender')->dropDownList($genders,['prompt' => 'Gender']); ?>
            </div>
        </div>
        <div class="col-field-name zipcode">
            <?= $form->field($profile, 'zip_code')->textInput(array('placeholder' => '46140','maxlength'=>5)); ?>
        </div>
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
        <div class="btn-control">
            <p>Update & Continue</p>
        </div>
        <?/*= Html::submitButton('Update & Continue', ['class' => 'btn btn-primary btn-control']) */?>

    <?php ActiveForm::end(); ?>


</div>