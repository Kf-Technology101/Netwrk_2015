<?php use yii\helpers\Url; ?>
<div id="create_topic" data-city="<?php echo $city_id ?>" <?php if ($data->status == 0){ echo 'data-zipcode="'.$data->zipcode.'" data-lat="'.$data->lat.'" data-lng="'.$data->lng.'" data-name-city="'.$data->city_name.'"'; } ?>>
    <div class="header">
        <div class="back_page">
            <!-- <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>"> -->
            <!-- <p><a href="#"><i class="fa fa-arrow-circle-left"></i> Back </a></p> -->
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title"><?= $city->zip_code ?> > Build a channel & add lines to it</span>
        </div>
    </div>
    <div class="container">

        <div class="topic">
            <p class="title"> Channel </p>
            <input type="text" class="name_topic" maxlength="128" placeholder="Channel Title">
        </div>
        <div class="post">
            <div class="post-title">
                <p class="title"> Line </p>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">#</span>
                    <input type="text" class="name_post" maxlength="128" placeholder="Head-line">
                </div>
            </div>
            <div class="post-message">
                <p class="title"> Message </p>
                <textarea class="message" placeholder="Don't be shy! Say something!" maxlength="1024"></textarea>
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