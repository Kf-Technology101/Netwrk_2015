<?php use yii\helpers\Url; ?>
<div id="post_message" data-post_id="<?= $post->post_id?>"
     data-lat="<?php echo $post->city_lat; ?>"
     data-lng="<?php echo $post->city_lng; ?>">
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title">Message your area</span>
        </div>
    </div>
    <div class="container">
        <div class="post">
            <div class="post-message-line">
                <p class="title"> Line </p>
                <input type="text" name="line" class="form-control line-input" value="<?php echo $post->post_title;?>" disabled="disabled" />
            </div>

            <div class="post-location-content">

            </div>

            <div class="post-title">
                <p class="title"> Message </p>
                <textarea class="post_message name_post_textarea" placeholder="Message" maxlength="128" id="postMessage"></textarea>
            </div>
        </div>
        <div class="btn-control">
            <div class="save disable">
                <span>Post</span>
                <i class="fa fa-check"></i>
            </div>
        </div>
    </div>
</div>
<script id="message-location-template" type="text/x-underscore-template">
    <section class="post-location-wrapper">
        <p class="title"> Location </p>
        <input type="text" name="location" class="form-control location-input" value="<%= data.location %>" disabled="disabled"  />
        <input type="hidden" name="formatted_address" value="<%= data.formatted_address %>" />
        <div id="messageLocationImage" class="line-location-image-wrapper hide">
            <img src="" class="line-location-image" alt="" />
        </div>
    </section>
</script>