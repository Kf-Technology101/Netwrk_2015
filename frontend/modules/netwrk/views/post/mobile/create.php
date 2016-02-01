<?php use yii\helpers\Url; ?>
<div id="create_post" data-topic="<?= $topic->id?>" data-city="<?= $city->id ?>">
    <div class="header">
        <div class="back_page">
            <!-- <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>"> -->
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title"><?= $city->zip_code ?> > Create a Post</span>
        </div>
    </div>
    <div class="container">
        <div class="post">
            <div class="post-title">
                <p class="title"> Post </p>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">#</span>
                    <input type="text" class="name_post" maxlength="128" placeholder="Post Title">
                </div>
            </div>
            <div class="post-message">
                <p class="title"> Message </p>
                <textarea class="message" placeholder="Type message here..." maxlength="1024"></textarea>
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
    </div>
</div>