<?php use yii\helpers\Url; ?>
<div class="modal modal-profile" id='modal_profile'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="header">
            <div class="back-page">
              <span><i class="fa fa-arrow-circle-left"></i> Back </span>
            </div>
            <div class="title-page">
                <span class="title">Profile</span>
            </div>
          </div>
      </div>
      <div class="modal-body profile-container">
        <div class="profile-info">

        </div>

        <div class="profile-activity-wrapper">
            <div class="fav-communities-wrapper">
                <div class="activity-header pull-left">Favorite Communities </div>
                <div class="seperator-line pull-right">
                    <hr>
                </div>
            </div>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sit amet quam tortor. Donec sed neque vitae lacus hendrerit tempor. Phasellus arcu mi, varius vitae dignissim eget, pellentesque vel dui. Vivamus vitae sagittis mi. Duis ut risus luctus, sagittis odio at, tempor nisl. Vivamus viverra ornare viverra. Quisque lacus nulla, iaculis ut elementum at, gravida dapibus nunc. Suspendisse in interdum felis, sit amet pretium urna. Quisque tincidunt libero eget augue porta rhoncus. Etiam suscipit placerat metus. Mauris a neque in libero ultrices volutpat eget eget dui.

            Pellentesque vulputate sodales urna et vulputate. Fusce lobortis nibh et ligula vehicula convallis. Donec dui tellus, posuere et iaculis in, imperdiet nec massa. Integer gravida dapibus blandit. Vestibulum non augue ut risus mollis efficitur eget in leo. Maecenas gravida, odio sed finibus venenatis, mauris arcu venenatis dolor, nec vehicula nulla felis nec urna. Pellentesque id dolor sagittis, cursus lorem ut, lobortis tortor. Maecenas aliquam massa dolor, nec pharetra nulla porta non. Suspendisse potenti. Mauris magna turpis, malesuada at venenatis vel, tincidunt ut arcu. Vestibulum et turpis mollis, laoreet mauris ac, faucibus est. Donec pulvinar, nisl quis pulvinar pretium, ipsum augue ullamcorper orci, nec luctus diam ante sed metus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.

            Etiam at risus porttitor, condimentum dui sit amet, maximus leo. Aenean eu felis nunc. Nulla et pulvinar odio. Nam viverra neque et est dictum, nec blandit ante efficitur. Sed aliquam felis commodo dolor sodales malesuada ut cursus neque. Aliquam lorem dui, vulputate eget ante sed, rutrum sodales arcu. Proin ipsum ex, condimentum et urna vitae, efficitur finibus nunc. Nunc lacinia enim id risus elementum maximus. Pellentesque dui quam, condimentum in rutrum sed, fermentum vel massa. Donec in est quis mi pulvinar venenatis nec vel risus. Aliquam sodales est ut purus condimentum molestie. Sed eleifend dui quis risus commodo, vel mollis orci vehicula. Sed id metus risus. Pellentesque vel laoreet sem, a dignissim augue. Phasellus lacus urna, facilisis et ultrices sed, vulputate vel arcu.
        </div>
    </div>
  </div>
</div>
</div>
<div class="modal" id='modal_change_profile_picture'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body container_chagne_avatar">
                <div class="image-preview">
                    <p>IMAGE PREVIEW</p>
                    <div class="preview_img"></div>
                    <div class="preview_img_ie"></div>
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
<script id="profile_info" type="text/x-underscore-template">
  <div class="cover-photo">
      <img src="<?= Url::to('@web/img/background/cover-bg.png'); ?>"/>
      <div class="change-cover"><i class="fa fa-camera"></i> Edit cover image</div>
  </div>
  <div class="profile-picture pull-left">
      <div class="img-user text-center"><img src="<%= data.image %>"></div>
      <div class="change-profile">
          <i class="fa fa-camera"></i>
      </div>
  </div>
  <div class="user-details-wrapper">
      <div class="user-details pull-left">
          <div class="user-name"><%= data.username %>, <%= data.year_old %></div>
          <div class="user-location">Bloomington, Indiana, U.S.A.</div>
      </div>
      <div class="btn-group profile-dropdown pull-left" role="group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-gears"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-right">
              <li class=''><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
              <li class=''><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
              <li class=''><a><i class='fa fa-user'></i> My profile info</a></li>
              <li class=''><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>

          </ul>
      </div>
      <div class="brillant pull-left">
          <div class="count">
              <span>0</span>
          </div>
      </div>
  </div>
</script>

