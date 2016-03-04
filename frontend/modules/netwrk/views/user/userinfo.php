<?php use yii\helpers\Url;?>
<span id='user_avatar_wrapper'></span>
<script id="user_info_dropdown" type="text/x-underscore-template" >
    <div id='user_avatar_dashboard' class='avatar-dropdown-dashboard'>
        <div class='user-avatar-thumbnail'><img class='img_avatar' src='<?= Url::to("@web/") ?><%= user_info.avatar %>' /></div>
        <i class='fa fa-caret-down'></i>
        <ul>
            <li class='avatar-dropdown-menu'><a><i class="fa fa-tachometer"></i> Dashboard</a></li>
            <li class='avatar-dropdown-menu'><a href="javascript:" class="profile-trigger"><i class="fa fa-user"></i> Profile</a></li>
            <li class='avatar-dropdown-menu sign-out'><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-sign-out"></i> Sign Out</a></li>
            <li class='avatar-dropdown-menu'><a><i class='fa fa-question-circle'></i> Help</a></li>
        </ul>
    </div>
</script>