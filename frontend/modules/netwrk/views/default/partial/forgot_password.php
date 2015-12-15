<?php 
    use yii\helpers\Url; 
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use frontend\modules\netwrk\models\forms\ForgotForm;
?>
<!-- <button class="btn btn-default btn-sm" data-toggle="modal" data-target=".forgotPassword">forgot password</button> -->
<div class="modal fade forgotPassword" id='forgot-password'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="title">
                        <p> Forgot your password</p>
                    </div>
                </div>
            </div>
            <div class="alert alert-success">
                <p></p>
            </div>
            <div class="modal-body">
                <p class="description">Please enter the email address associated and we will send you an email with a link to reset your password</p>
                <?php $form = ActiveForm::begin([
                    'id' => 'forgot-form',
                    'fieldConfig' => [
                        'template' => "{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],
                ]);
                $model = new ForgotForm();
                ?>
                    <?= $form->field($model, 'email')->textInput(array('placeholder' => 'Email address','class'=>'form-control email')) ?>
                <?php ActiveForm::end(); ?>
                <div class="send-email">Send Email</div>
            </div>
        </div>
    </div>
</div>