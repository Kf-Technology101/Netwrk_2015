<?php use yii\helpers\Url; ?>
<?php if ($post->post_type == 1 ) { ?>
<div id="post_chat" class='post-id-<?= $post->id ?>' data-topic="<?= $post->topic->id ?>" data-post="<?= $post->id ?>" data-user-login="<?= $current_user ?>" data-chat-type='1'>
<?php } else { ?>
<div id="post_chat" class='post-id-<?= $post->id ?>'  data-post="<?= $post->id ?>" data-user-login="<?= $current_user ?>" data-chat-type='0'>
<?php } ?>
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title">
            <?php if ($post->post_type == 1 ) { ?>
                <span><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a></span>
                <span><i class="fa fa-angle-right"></i><?= $post->topic->title ?></span>
                <span><i class="fa fa-angle-right"></i><?= $post->title ?></span>
            <?php } else { ?>
            <span class='title-user-private'><?= $user_id->user->profile->first_name.' '.$user_id->user->profile->last_name; ?></span>
            <?php } ?>

            </span>
        </div>
    </div>

    <div class="container_post_chat"></div>
    <img src='<?= Url::to("@web/img/icon/ajax-loader.gif")?>' class='loading_image' />
    <div class="nav_input_message">
        <?php if(Yii::$app->user->isGuest){?>
            <div class="send_message input-group" data-url="<?= $url ?>">
                <input type="text" class="form-control" placeholder="You have to log in to chat" disabled="true">
                <div class="input-group-addon login" id="sizing-addon2">Login</div>
            </div>
        <?php }else{ ?>
            <form id='msgForm' class="send_message input-group">
                <textarea type="text" class="form-control" placeholder="Type message here..." maxlength="1024"></textarea>
                <div id='file_btn' class="input-group-addon paper"><i class="fa fa-paperclip"></i></div>
                <input type='file' id='file_upload' name='file_upload' style="display:none" />
                <div class="input-group-addon emoji dropup">
                    <i class="fa fa-smile-o dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button" ></i>
                    <ul class="dropdown-menu"></ul>
                </div>
                <div class="input-group-addon send" id="sizing-addon2">Send</div>
            </form>
        <?php } ?>

    </div>
</div>
<script id="list_emoji" type="text/x-underscore-template">
    <% _.each(emoji,function(i,e){ %>
        <li data-value="<%= i %>" data-toggle="tooltip" title="<%= i %>"><%= i %></li>
    <% })%>
</script>
<script id="message_chat" type="text/x-underscore-template">
    <% if (msg.id == UserLogin){ %>
        <div class="message_send message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
   <% }else{ %>
        <div class="message_receiver message" data-img="<?#= Url::to('@web/img/icon/timehdpi.png'); ?>">
    <% } %>
        <div class="user_thumbnail" data-user-id='<%= msg.id %>'>
            <div class="avatar">
                <img src="<%= baseurl %><%=  msg.avatar %>">
            </div>
        </div>
        <div class="content_message">
            <% if(msg.msg_type == 1) { %>
                <p class="content"><%= msg.msg %></p>
            <% }else if(msg.msg_type == 2) { %>
                <a class='img_chat_style' href='<?= Url::to("@web/img/uploads/") ?><%= msg.msg %>' target='_blank'><img src='<?= Url::to("@web/img/uploads/") ?><%= msg.msg %>' /></a>
            <% } else { %>
                <a class='file-uploaded-link' href='<?= Url::to("@web/files/uploads/") ?><%= msg.msg %>' target='_blank'><%= msg.msg %></a>
            <% } %>
                <p class="time"><%= msg.created_at %></p>
            </div>
        </div>
    </div>
</script>
