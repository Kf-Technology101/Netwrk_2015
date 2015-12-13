<?php 
    use yii\helpers\Url; 
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use frontend\modules\netwrk\models\User;
    use frontend\modules\netwrk\models\UserKey;
?>
<!-- <button class="btn btn-default btn-sm" data-toggle="modal" data-target=".resetPassword">reset password</button> -->
<div class="modal fade resetPassword" id='reset-password'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="title">
                        <p> Reset your password</p>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <p><?= "Password has been reset" ?></p>
                </div>

                <div class="alert alert-danger">
                    <p><?= "Invalid key" ?></p>
                </div>
                <?php
                    $user = new User();
                    $user->setScenario("reset");

                    $form = ActiveForm::begin(['id' => 'reset-form']); 
                ?>
                    <?= $form->field($user, 'newPassword')->passwordInput() ?>
                    <?= $form->field($user, 'newPasswordConfirm')->passwordInput() ?>
                    <div class="reset">Reset</div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>