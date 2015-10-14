<?php use yii\helpers\Url; ?>
<div id="create_topic" data-city="<?php echo $city->id ?>">
    <div class="header">
        <div class="back_page">
            <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>">
        </div>
        <div class="title_page">
            <span class="title"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>">Create a Topic</span>
        </div>
    </div>
    <div class="container">
        
        <div class="topic">
            <p class="title"> Topic </p>
            <input type="text" class="name_topic" maxlength="128" placeholder="Topic title">
        </div>
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