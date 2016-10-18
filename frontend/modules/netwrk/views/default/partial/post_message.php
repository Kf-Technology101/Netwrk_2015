<?php use yii\helpers\Url; ?>
<div class="modal" id="post_message" data-lat="" data-lng="" data-post-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="name_user">
                        <p> Message your area </p>
                    </div>
                    <div class="back_page">
                        <span><i class="fa fa-times-circle"></i></span>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic">
                    <div class="post">
                        <input type="hidden" name="post_id" id="post_id" value=""/>

                        <div class="post-message-line">
                            <p class="title"> Line </p>
                            <input type="text" name="line" class="form-control line-input" value="" disabled="disabled" />
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
                            <span>Build</span>
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                </div>
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