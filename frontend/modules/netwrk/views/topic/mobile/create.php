<?php use yii\helpers\Url; ?>
<div id="create_topic" data-topic_id="<?php echo $topic->topic->id; ?>" data-city="<?php echo $city_id ?>" <?php if ($data->status == 0){ echo 'data-zipcode="'.$data->zipcode.'" data-lat="'.$data->lat.'" data-lng="'.$data->lng.'" data-name-city="'.$data->city_name.'"'; } ?>>
    <div class="header">
        <div class="back_page">
            <!-- <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>"> -->
            <!-- <p><a href="#"><i class="fa fa-arrow-circle-left"></i> Back </a></p> -->
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title"><?= $city->zip_code ?> > Create a Topic</span>
        </div>
    </div>
    <div class="container">

        <div class="topic">
            <p class="title"> Topic </p>
            <input type="text" class="name_topic" maxlength="128" placeholder="Topic Title" value="<?php echo isset($topic->topic->title) ? $topic->topic->title: '' ?>">
        </div>
        <div class="post">
            <div class="post-title">
                <p class="title"> Post </p>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">#</span>
                    <input type="text" class="name_post" data-post_id="<?php echo isset($topic->post->post_id) ? $topic->post->post_id : '' ?>" maxlength="128" placeholder="Post Title" value="<?php echo isset($topic->post->post_title) ? $topic->post->post_title : '' ?>">
                </div>
            </div>
            <div class="post-message">
                <p class="title"> Message </p>
                <textarea class="message" placeholder="Type message here..." maxlength="1024"><?php echo isset($topic->post->content) ? $topic->post->content : '' ?></textarea>
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