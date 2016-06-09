<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\netwrk\controllers\ProfileController;
    use frontend\modules\netwrk\models\User;
    use frontend\modules\netwrk\models\PasswordSetting;
?>
<div class="modal modal-profile" id='modal_password_setting'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="header">
            <div class="back-page">
              <span><i class="fa fa-arrow-circle-left"></i> Back </span>
            </div>
            <div class="title-page">
                <span class="title">Password setting</span>
            </div>
          </div>
      </div>
      <div class="modal-body container">
        <?php
          $form = ActiveForm::begin([
            'id' => 'password_setting_form',
            'options' => ['class' => 'form-password-setting form-horizontal'],
            'fieldConfig' => [
              'template' => "<div class=\"col-md-12\">{input}</div>\n<div class=\"col-md-12\">{error}</div>",
              'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
          ]);
          $user = new User(["scenario" => "password_setting"]);
        ?>
          <div id="password_setting_success" class="alert alert-success hide"></div>
          <div class="field">
              <p class="title"> Current password </p>
              <?= $form->field($user, 'currentPassword')->passwordInput(array('placeholder' => 'Current Password','autofocus'=>true)) ?>
          </div>
          <div class="field">
              <p class="title"> New password </p>
              <?= $form->field($user, 'newPasswordProfile')->passwordInput(array('placeholder' => 'New Password')) ?>
          </div>
          <div class="field">
              <p class="title"> Confirm password </p>
              <?= $form->field($user, 'newPasswordConfirmProfile')->passwordInput(array('placeholder' => 'Confirm Password')) ?>
          </div>
          <div class="btn-control">
              <div class="reset pull-left">
                  <p>Reset</p>
              </div>
              <div class="update pull-right">
                  <p>Update</p>
              </div>
              <div class="clearfix"></div>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
