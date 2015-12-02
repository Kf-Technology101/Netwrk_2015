<?php use yii\helpers\Url; ?>
<div id="create_topic" data-city="<?php echo $city_id ?>" <?php if ($data->status == 0){ echo 'data-zipcode="'.$data->zipcode.'" data-lat="'.$data->lat.'" data-lng="'.$data->lng.'" data-name-city="'.$data->city_name.'"'; } ?>>
    <div class="header">
        <div class="back_page">
            <!-- <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>"> -->
            <p><a href="#"><i class="fa fa-arrow-circle-left"></i> Back </a></p>
        </div>
        <div class="title_page">
            <span class="title">Create a Topic</span>
        </div>
    </div>
    <div class="container">
        
        <div class="topic">
            <p class="title"> Topic </p>
            <input type="text" class="name_topic" maxlength="128" placeholder="Topic Title">
        </div>
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