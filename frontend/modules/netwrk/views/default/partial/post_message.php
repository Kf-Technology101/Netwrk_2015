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

                        <div class="nav_input_message">
                            <div class="send_message input-group">
                                <textarea type="text" class="form-control post_message name_post_textarea" placeholder="Type message here..." maxlength="1024" id="postMessage"></textarea>
                                <div id='msgFileBtn' class="input-group-addon paper"><i class="fa fa-paperclip"></i></div>
                                <input type='file' id='msgFileUpload' name='file_upload' style="display:none" />
                                <div class="input-group-addon emoji dropup">
                                    <i class="fa fa-smile-o dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button" ></i>
                                    <ul class="dropdown-menu"></ul>
                                </div>
                                <div class="input-group-addon send">Send</div>
                            </div>
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
<script id="message_list_emoji" type="text/x-underscore-template">
    <% _.each(emoji,function(i,e){ %>
    <li data-value="<%= i %>" data-toggle="tooltip" title="<%= i %>"><%= i %></li>
    <% })%>
</script>