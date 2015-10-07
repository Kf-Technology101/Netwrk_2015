<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_meet'>
  <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/netwrk_btn.png'); ?>"/></div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="name_user">
            <p class="default"> NO DATA</p>
          </div>
          <div class="sidebar">
            <table class="filter_sidebar">
                <tr>
                    <td class="meeting active">Meet</td>
                    <td class="setting">Settings</td>
                    <td class="profile ">Profile</td>
                </tr>
            </table> 
        </div>
      </div>
      <div class="modal-body container_meet">

        <div class="page" id="meeting">
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
        <div class="page" id="meet_setting">
            <div class="show_me">
              <p>Show me:</p>
              <div class="radio_gender">
                  <input type="radio" class="input_radio" name='sex' id="all" value='All'><label for='all'> All</label>
                  <input type="radio" class="input_radio" name='sex'id="female" value='Female'><label for="female"> Female</label>
                  <input type="radio" class="input_radio" name='sex' id="male" value='Male'> <label for="male"> Male </label>
              </div>
            </div>
            <div class="search_area">
                <div class="head">
                    <p class="title">Limit search Area To:</p>
                    <p class="value"></p>
                </div>
                
                <div id="circles-slider-area"></div>
            </div>
            <div class="search_age">
              <div class="head">
                  <p class="title">Limit search Age:</p>
                  <p class="value"></p>
              </div>
            
              <div id="circles-slider-age"></div>
              
            </div>
            <div class="btn-control">
                  <div class="cancel disable">
                      <p>Reset</p>
                  </div>
                  <div class="save disable">
                      <i class="fa fa-check"></i>
                      <span>Save</span>
                  </div>
              </div>
        </div>
        <div class="page" id="user_setting">

        </div>
      </div>
    </div>
  </div>

</div>
<div class="modal" id='modal_change_avatar'>
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-body container_chagne_avatar">
               <div class="image-preview">
                   <p>IMAGE PREVIEW</p>
                   <img class="preview_image" src="" />
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
                            <input type="file" class="input_image" name='image' accept="image/jpg,image/png,image/jpeg,image/gif">
                        <!-- </form> -->
                        <?php \yii\widgets\ActiveForm::end(); ?>
                        <p>Browse</p>
                    </div>
               </div>
           </div>
       </div>
   </div> 
</div>
<script id="name_user" type="text/x-underscore-template">
    
    <p class="user_meet_<%= vt %> name" data-meet="<%= user.met %>"><%= user.username %></p>
</script>
<script id="list_user" type="text/x-underscore-template">
  <div class="user_item user_meet_<%= vt %> active">
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
              <td class="title">Posts:</td>
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

<script id="user_info" type="text/x-underscore-template">
    <div class="user_avatar">
        <img src="<%= data.image %>">
        <div class="change_avatar">
            <i class="fa fa-cog"></i>
        </div>
    </div>
    <div class="user_information">
        <div class="field_info">
            <p> Birthday: </p>
            <input type="text" name='birthday' class="birthday" maxlength="10" value="<%= data.age %>" />
        </div>
        <div class="field_info">
            <p> Zip Home: </p>
            <input type="text" class="zip_code" maxlength="5" value="<%= data.zip %>" />
        </div>
        <div class="field_info">
            <p> Work: </p>
            <input type="text" class="work" maxlength="128" value="<%= data.work %>"/>
        </div>
        <div class="field_info">
            <p> About: </p>
            <textarea class="about" maxlength="2000"><%= data.about %></textarea>
        </div>
    </div>
    <div class="btn-control">
        <div class="cancel disable">
            <p>Reset</p>
        </div>
        <div class="save disable">
            <i class="fa fa-check"></i>
            <span>Save</span>
        </div>
    </div>
</script>
<script id="user_name_current" type="text/x-underscore-template">
    <p class="name"><%= data.username %></p>
</script>
