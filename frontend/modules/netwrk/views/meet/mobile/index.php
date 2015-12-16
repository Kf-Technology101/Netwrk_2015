<?php use yii\helpers\Url; ?>

<div id="show_meet">
  <div class="sidebar">
     <table class="filter_sidebar">
          <tr>
              <td class="meeting active">Meet</td>
              <td class="setting">Settings</td>
              <td class="profile ">Profile</td>
          </tr>
     </table> 
  </div>
  <div class="container_meet">
    <div class='page' id="meeting_page">
      <div class="user_list"></div>
      <p class="no_data">There is no relevant user. Try broadening your Meet Settings.</p>
      <div class="footer-btn">
            <table class="control-btn">
              <tr>
                <td class="back disable">
                  <i class="fa fa-angle-left"></i>
                  <span>Back</span>
                </td>
                <td class="meet">
                  <!-- <i class="fa fa-user"></i> -->
                  <span>Meet</span>
                </td>
                <td class="met">
                  <!-- <i class="fa fa-user"></i> -->
                  <span>Met</span>
                </div>
                <td class="next">
                  <span>Next</span>
                  <i class="fa fa-angle-right"></i>
                </td>
              </tr>
            </table>
          </div>
    </div>
    <div class='page' id="meet_setting">
        <div class="show_me">
            <p>Show me</p>
            <div class="radio_gender">
                <input type="radio" class="input_radio" name='sex' id="all" value='All'><label for='all'> All</label>
                <input type="radio" class="input_radio" name='sex'id="female" value='Female'><label for="female"> Female</label>
                <input type="radio" class="input_radio" name='sex' id="male" value='Male'> <label for="male"> Male </label>
            </div>
        </div>
        <div class="search_area">
            <div class="head">
                <p class="title">Limit Search Area To</p>
                <p class="value"></p>
            </div>
            <div id="circles-slider-area"></div>
        </div>
        <div class="search_age">
            <div class="head">
                <p class="title">Limit Search Age</p>
                <p class="value"></p>
            </div>
            
            <div id="circles-slider-age"></div>
        </div>
        <div class="btn-control">
                <div class="cancel disable">
                    <p>Reset</p>
                </div>
                <div class="save disable">
                    <span>Save</span>
                    <i class="fa fa-check"></i>
                </div>
            </div>
    </div>
    <div class='page' id="user_setting">
        <div class="modal" id='modal_change_avatar'>
           <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-body container_chagne_avatar">
                       <div class="image-preview">
                           <p>IMAGE PREVIEW</p>
                           <div class="preview_img"></div>
                       </div>
                       <div class="btn-control-modal">
                            <div class="cancel">
                                <p>Cancel</p>
                            </div>
                            <div class="save disable">
                                <i class="fa fa-check"></i>
                                <span>Save</span>
                            </div>
                            <div class="browse">
                                <?php 
                                    $form = \yii\widgets\ActiveForm::begin([
                                        'action' => Url::to(['/netwrk/setting/upload-image']),
                                        'options' => [
                                            'id' => 'upload_image',
                                            'enctype' => 'multipart/form-data',
                                        ]
                                    ]);
                                ?>
                                <!-- <form id="upload_image" method="post" action="<?= Url::to(['/netwrk/setting/upload-image']) ?>" enctype="multipart/form-data"> -->
                                    <input type="file" id="input_image" name='image' accept="image/jpg,image/png,image/jpeg,image/gif">
                                <!-- </form> -->
                                <?php \yii\widgets\ActiveForm::end(); ?>
                                <p>Browse</p>
                            </div>
                       </div>
                   </div>
               </div>
           </div>
        </div>
    </div>
  </div>
</div>

<script id="name_user" type="text/x-underscore-template">
    <span class="user_meet_<%= vt %> name" data-meet="<%= user.met %>"><%= user.username %></span>
</script>

<script id="list_user" type="text/x-underscore-template">
  <div class="user_item user_meet_<%= vt %>">
      <div class="avatar-image"><img src="<%= user.image %>"/></div>
      <div class="box-infomation">
        <div class="table">
          <div class="user">
            <%= user.username %>, <%= user.year_old %>
          </div>
          <div class="info-detail">
            <div class="work">
              <span><i class="fa fa-briefcase"></i></span>
              <p class="title">Work</p>
              <p class="text"><%= user.work %></p>
            </div>
            <div class="about">
              <span><i class="fa fa-info-circle"></i></span>
              <p class="title">About</p>
              <p class="text"><%= user.about %></p>
            </div>
            <div class="post">
              <span>
                <i><img src="<?= Url::to('@web/img/icon/post-icon-desktop.png') ?>"></img></i>
              </span>
              <p class="title">Posts</p>
              <p class="text">
                <% _.each(user.post,function(p){ %>
                  <span><%= p.title %></span>
                <% }); %>
              </p>
            </div>
          </div>
        </div>
        <!-- table -->
        <div class="brillant">
          <div class="count"><span><%= user.brilliant %></span></div>
          <p>Brilliant</p>
        </div>
      </div>
  </div>
</script>

<script id="user_info" type="text/x-underscore-template">
    <div class="user_avatar">
        <div class="img_user"><img src="<%= data.image %>"></div>
        <div class="change_avatar">
            <i class="fa fa-camera"></i>
        </div>
    </div>
    <div class="user_information">
        <div class="user"><%= data.username %>, <%= data.year_old %></div>
        <div class="field">
            <p><i class="fa fa-birthday-cake"></i> Birthday </p>
            <input type="text" class="birthday" maxlength="10" value="<%= data.age %>" />
        </div>
        <div class="field field-margin">
            <p><i class="fa fa-map-marker"></i> Home Zip </p>
            <input type="text" class="zip_code" maxlength="5" value="<%= data.zip %>" />
        </div>
        <div class="field_info">
            <p><i class="fa fa-briefcase"></i> Work </p>
            <input type="text" class="work" maxlength="128" value="<%= data.work %>"/>
        </div>
        <div class="field_info">
            <p><i class="fa fa-info-circle"></i> About </p>
            <textarea class="about" maxlength="2000"><%= data.about %></textarea>
        </div>
    </div>
    <div class="btn-control">
        <div class="cancel disable">
            <p>Reset</p>
        </div>
        <div class="save disable">
            <span>Save</span>
            <i class="fa fa-check"></i>
        </div>
    </div>
</script>

<script id="user_name_current" type="text/x-underscore-template">
    <span class="name"><%= data.username %></span>
</script>
