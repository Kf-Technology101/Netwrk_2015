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
            <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>
                <?= $form->field($user, 'newPassword')->passwordInput(array('placeholder' => 'New password','autofocus'=>true)) ?>
                <?= $form->field($user, 'newPasswordConfirm')->passwordInput(array('placeholder' => 'Confirm Password')) ?>
                <div class="form-group">
                    <?= Html::submitButton("Reset", ['class' => 'btn btn-primary reset']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>
</div>