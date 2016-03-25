<?php use yii\helpers\Url; ?>

<section id="Profile">
    <section class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title">Profile</span>
        </div>
        <div class="setting">
            <span class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i></span>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class=''><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
                <li class=''><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
                <li class=''><a href="javascript:" id="my_profile_info"><i class='fa fa-user'></i> My profile info</a></li>
                <li class=''><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>
            </ul>
        </div>
    </section>
    <section class="profile-container">
        <section class="profile-info">
            <div class="cover-photo">
                <img src="/img/background/cover-bg.png">
                <div class="change-cover"><i class="fa fa-camera"></i> Edit cover image</div>
            </div>
            <div class="profile-picture pull-left">
                <div class="img-user text-center"><img src="/img/icon/no_avatar.jpg"></div>
                <div class="change-profile">
                    <i class="fa fa-camera"></i>
                </div>
            </div>
            <div class="user-details-wrapper">
                <div class="user-details pull-left">
                    <div class="user-name">yogesh mahale, 28</div>
                    <div class="user-location">, , </div>
                </div>
                <div class="btn-group profile-dropdown pull-left" role="group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-gears"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class=""><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
                        <li class=""><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
                        <li class=""><a href="javascript:" id="my_profile_info"><i class="fa fa-user"></i> My profile info</a></li>
                        <li class=""><a href="http://local.netwrk.com/netwrk/user/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>
                    </ul>
                </div>
                <div class="brillant pull-left">
                    <div class="count">
                        <span>0</span>
                    </div>
                </div>
            </div>
        </section>
    </section>


</section>

