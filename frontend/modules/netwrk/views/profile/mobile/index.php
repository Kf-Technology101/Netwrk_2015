<?php use yii\helpers\Url; ?>

<div id="Profile" data-topic="<?= $topic->id ?>" <?php if (!empty($city)) { ?>data-city="<?= $city ?>"<?php } ?>>
    <div class="header">
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
    </div>

</div>

