<?php 
    use yii\helpers\Url; 
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div id='reset-password'>
    <div class="header">
        <div class="title">
            <img src="<?= Url::to('@web/img/icon/netwrk-logo-blue.png'); ?>">
            <p> Reset your password</p>
        </div>
    </div>
    <div class="container">
        <?php if (!empty($success)): ?>

            <div class="alert alert-success">
                <p><?= "Password has been reset" ?></p>
            </div>

        <?php elseif (!empty($invalidKey)): ?>
            <div class="alert alert-danger">
                <p><?= "Invalid key" ?></p>
            </div>
        <?php else: ?>
            <?php $form = ActiveForm::begin([
                            'id' => 'reset-form',
                             'fieldConfig' => [
                                'template' => "<div class=\"col-md-12 no-padding\">{input}</div>\n<div class=\"col-lg-12 no-padding\">{error}</div>",
                            ],
                        ]); ?>
                <div class="form-reset">
                    <div class="field-name username">
                        <p class="title"> New Password </p>
                        <?= $form->field($user, 'newPassword')->passwordInput(array('placeholder' => 'New password','autofocus'=>true)) ?>
                    </div>
                    <div class="field-name username">
                        <p class="title"> Confirm Password </p>
                        <?= $form->field($user, 'newPasswordConfirm')->passwordInput(array('placeholder' => 'Confirm Password')) ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton("Reset", ['class' => 'btn btn-primary reset']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>
</div>