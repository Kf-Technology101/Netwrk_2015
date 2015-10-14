<?php use yii\helpers\Url; ?>
<div id="create_post" data-topic="<?= $topic->id?>" data-city="<?= $city->id ?>">
    <div class="header">
        <div class="back_page">
            <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>">
        </div>
        <div class="title_page">
            <span class="title"><a href="<?php echo Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>"></a>Create a Post</span>
        </div>
    </div>
    <div class="container">
        <div class="post">
            <p class="title"> Post </p>
            <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2">#</span>
                <input type="text" class="name_post" maxlength="128" placeholder="Post title">
            </div>
            <p class="title"> Message </p>
            <textarea class="message" placeholder="Message..." maxlength="1024"></textarea>
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
</div>