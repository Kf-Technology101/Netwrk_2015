<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_meet'>
  <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/netwrk_btn.png'); ?>"/></div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="name_user">
          </div>
      </div>
      <div class="modal-body container_meet">
        <div class="user_list"></div>
        <div class="control-btn">

            <div class="back disable">
              <i class="fa fa-chevron-left"></i>
              <span>back</span>
            </div>

            <div class="next">
              <span>next</span>
              <i class="fa fa-chevron-right"></i>
            </div>

            <div class="meet">
              <!-- <i class="fa fa-user"></i> -->
              <span><img src="<?= Url::to('@web/img/icon/human.png'); ?>"/>meet</span>
            </div>
            <div class="met">
              <!-- <i class="fa fa-user"></i> -->
              <span><img src="<?= Url::to('@web/img/icon/human.png'); ?>"/>met</span>
            </div>
          </div>
      </div>
    </div>
  </div>

</div>
<script id="name_user" type="text/x-underscore-template">
    <p class="user_meet_<%= vt %>" data-meet="<%= user.met %>"><%= user.username %></p>
</script>
<script id="list_user" type="text/x-underscore-template">
  <div class="user_item user_meet_<%= vt %>">
      <div class="avatar-image"><img src="<%= user.image %>"/></div>
      <div class="box-infomation table-responsive">
        <table class="table">
          <tbody>
            <tr>
              <td class="title">Age:</td>
              <td class="text"><%= user.year_old %></td>
            </tr>
            <tr>
              <td class="title">Work:</td>
              <td class="text"><%= user.work %></td>
            </tr>
            <tr class="about">
              <td class="title">About:</td>
              <td class="text"><%= user.about %></td>
            </tr>
            <tr class="post">
              <td class="title">Post:</td>
              <td class="text">
                <% _.each(user.post,function(p){ %>
                  <span><%= p %></span>
                <% }); %>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="brillant">
          <div class="count"><span>1</span></div>
          <p>Brilliant</p>
        </div>
      </div>
  </div>
</script>
