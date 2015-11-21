<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_meet'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="name_user"><p> Topics</p></div>
      </div>
      <div class="modal-body container_meet">
        <div id="user_list">
          <div class="avatar-image">
            <img src="<?= Url::to('@web/img/icon/no_avatar.jpg'); ?>"/>
          </div>

          <div class="box-infomation table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <td class="title">Age:</td>
                  <td class="text">22</td>
                </tr>
                <tr>
                  <td class="title">Work:</td>
                  <td class="text">Dentist</td>
                </tr>
                <tr class="about">
                  <td class="title">About:</td>
                  <td class="text">I'm also studying nursing.In my free time i love short walk of long piese</td>
                </tr>
                <tr>
                  <td class="title">Post:</td>
                  <td class="text"><span>#abc</span><span>#abc</span><span>#abc</span><span>#abc</span><span>#abc</span></td>
                </tr>
              </tbody>
            </table>
            <div class="brillant">
              <div class="count"><span>1</span></div>
              <p>Brillant</p>
            </div>
          </div>

          <div class="control-btn">
            <div class="back">
              <i class="fa fa-angle-left"></i>
              <span>back</span>
            </div>
            <div class="next">
              <span>next</span>
              <i class="fa fa-angle-right"></i>
            </div>
            <div class="meet">
              <i class="fa fa-user"></i>
              <span>meet</span>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>