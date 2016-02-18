<?php use yii\helpers\Url;?>
<script id="account_nav_dropdown" type="text/x-underscore-template" >
    <div id='account_nav_wrapper' class="btn-group profile-dropdown" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="navigation-icon fa fa-user"></i>
            <div class="navigation-text">Profile</div>
        </button>
        <ul class="dropdown-menu">
            <li class='avatar-dropdown-menu'><a><i class="fa fa-tachometer"></i> Dashboard</a></li>
            <li class='avatar-dropdown-menu'><a><i class="fa fa-user"></i> Profile</a></li>
            <li class='avatar-dropdown-menu sign-out'><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-sign-out"></i> Sign Out</a></li>
            <li class='avatar-dropdown-menu'><a><i class='fa fa-question-circle'></i> Help</a></li>
        </ul>
    </div>
</script>

