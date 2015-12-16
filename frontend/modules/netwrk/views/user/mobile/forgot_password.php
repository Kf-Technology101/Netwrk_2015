<?php 
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div id='forgot-password'>
    <div class="header">
        <div class="title">
            <img src="<?= Url::to('@web/img/icon/netwrk-logo-blue.png'); ?>">
            <p> Forgot your password</p>
        </div>
    </div>
    <div class="container">
        <?php if ($flash = Yii::$app->session->getFlash('Forgot-success')): ?>

        <div class="alert alert-success">
            <p><?= $flash ?></p>
        </div>
        <?php else: ?>
            <p class="description">Please enter the email address associated and we will send you an email with a link to reset your password</p>
                <?php $form = ActiveForm::begin([
                    'id' => 'forgot-form',
                    'fieldConfig' => [
                        'template' => "{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],
                ]); ?>
                <?= $form->field($model, 'email')->textInput(array('placeholder' => 'Email address','class'=>'form-control email')) ?>
                <?= Html::submitButton('Send Email', ['class' => 'send-email']) ?>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
        <!-- <div class="send-email disable">Send Email</div> -->
    </div>
</div>