<?php
    use yii\helpers\Url;
    use yii\web\Cookie;
    $cookies = Yii::$app->request->cookies;

    if (isset($cookies["nw_popover_chat_topic_title"])) {
        $popover_chat_topic_title = '';
        $chat_topic_title_popover = '';
    } else {
        $popover_chat_topic_title = 'popover-chat-topic-title';
        $chat_topic_title_popover = "Checkout this line's highlights in its channel";
    }
?>
<?php if ($post->post_type == 1 ) { ?>
<div id="post_chat" class='post-id-<?= $post->id ?> chat-box' data-topic="<?= $post->topic->id ?>" data-post="<?= $post->id ?>" data-user-login="<?= $current_user ?>" data-chat-type='1'>
<?php } else { ?>
<div id="private_chat" class='post-id-<?= $post->id ?>'  data-post="<?= $post->id ?>" data-user-login="<?= $current_user ?>" data-chat-type='0'>
<?php } ?>
    <div class="header">
        <?php if ($post->post_type == 1 ) { ?>
            <div class="chat-discussion-header">
                <div class="left-section">
                    <div class="popup-title-description chat-topic-trigger <?= $popover_chat_topic_title ?>"
                         title="<?= $post->topic->title ?>"
                         data-city-name="<?= $post->topic->city->name?>"
                         data-city="<?= $post->topic->city_id?>"
                         data-value="<?= $post->topic->id?>"
                         data-template='<div class="popover info-popover chat-topic-title-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><div class="popover-close-trigger" data-cookie="nw_popover_chat_topic_title" data-wrapper="popover-chat-topic-title">&times;</div></div><div class="popover-content"></div></div>'
                         data-placement="bottom"
                         data-content="<?= $chat_topic_title_popover ?>">
                            <?= $post->topic->title ?>
                    </div>
                </div>
                <div class="middle-section">
                    <i class="fa fa-align-justify"></i>
                </div>
                <div class="right-section active">
                    <div class='popup-title-name'>
                        <div class="fa fa-lg fa-minus"></div>
                        <?= $post->title ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="back_page">
                <span><i class="fa fa-arrow-circle-left"></i> Back </span>
            </div>
            <div class="title_page">
                <span class="title">
                    <span class='title-user-private'><?= $user_id->user->profile->first_name.' '.$user_id->user->profile->last_name; ?></span>
                </span>
            </div>
        <?php } ?>

        <!--<div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <?php /*if ($post->post_type == 1 ) { */?>
                <span class="title post-title">
                    <span class="popup-title-name"><?/*= $post->title */?></span>
                    <span class="popup-title-description chat-topic-trigger <?/*= $popover_chat_topic_title */?>"
                          title="<?/*= $post->topic->title */?>"
                          data-city-name="<?/*= $post->topic->city->name*/?>"
                          data-city="<?/*= $post->topic->city_id*/?>"
                          data-value="<?/*= $post->topic->id*/?>"
                          data-template='<div class="popover info-popover chat-topic-title-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><div class="popover-close-trigger" data-cookie="nw_popover_chat_topic_title" data-wrapper="popover-chat-topic-title">&times;</div></div><div class="popover-content"></div></div>'
                          data-placement="bottom"
                          data-content="<?/*= $chat_topic_title_popover */?>">
                        <?/*= $post->topic->title */?>
                    </span>
                </span>
            <?php /*} else { */?>
                <span class="title">
                    <span class='title-user-private'><?/*= $user_id->user->profile->first_name.' '.$user_id->user->profile->last_name; */?></span>
                </span>
            <?php /*} */?>
        </div>-->
    </div>

    <?php if ($post->post_type == 1) { ?>
        <div class="chat-feedback">
            <?= $this->render('@frontend/modules/netwrk/views/feedback/view') ?>
        </div>
    <?php } ?>

    <?php if ($post->post_type == 0 ) { ?>
        <div class="container_post_chat container_private_chat">
    <?php } else { ?>
        <div class="container_post_chat">
    <?php } ?>
    </div>
    <img src='<?= Url::to("@web/img/icon/ajax-loader.gif")?>' class='loading_image' />
    <div class="nav_input_message">
        <?php if(Yii::$app->user->isGuest){?>
            <div class="send_message input-group no-login" data-url="<?= $url ?>">
                <input type="text" class="form-control" placeholder="You have to log in to chat" disabled="true">
                <div class="input-group-addon login" id="sizing-addon2">Login</div>
            </div>
        <?php }else{ ?>
            <form id='msgForm' class="send_message input-group login">
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
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading<%= msg.msg_id %>">
                <div class="panel-title">
                    <a href="#collapse<%= msg.msg_id %>" role="button"
                        data-toggle="collapse" aria-controls="collapse<%= msg.msg_id %>"
                        class="<% if(msg.feedback_points < 0) { %>collapsed<%}%>">
                        <div class="message-minimized">
                            <span class="user-name"><%=  msg.name %></span>
                            <span class="feedback-img"></span>
                            <span class="time"><%= msg.created_at %></span>
                        </div>
                    </a>
                </div>
            </div>
            <div id="collapse<%= msg.msg_id %>" class="panel-collapse collapse <% if(msg.feedback_points >= 0) { %>in<%}%>" role="tabpanel" aria-labelledby="heading<%= msg.msg_id %>">
                <div class="panel-body">
                    <div class="chat-details">
                        <div class="user_thumbnail" data-user-id='<%= msg.id %>'>
                            <div class="avatar">
                                <img src="<%= baseurl %><%=  msg.avatar %>">
                            </div>
                        </div>
                        <div class="feedback-line"></div>
                        <div class="feedback feedback-trigger" data-parent=".post-id-<%= msg.post_id %>" data-object="ws_message" data-id="<%= msg.msg_id %>">F</div>
                        <p class="time"><%= msg.created_at %></p>
                    </div>

                    <div class="content_message">
                        <% if(msg.msg_type == 1) { %>
                            <p class="content"><%= msg.msg %></p>
                        <% }else if(msg.msg_type == 2) { %>
                            <a class='img_chat_style' href='<?= Url::to("@web/img/uploads/") ?><%= msg.post_id %>/<%= msg.msg %>' target='_blank'><img src='<?= Url::base(true)."/img/uploads/" ?><%= msg.post_id %>/<%= msg.msg %>' /></a>
                        <% } else { %>
                            <a class='file-uploaded-link' href='<?= Url::to("@web/files/uploads/") ?><%= msg.post_id %>/<%= msg.msg %>' target='_blank'><%= msg.msg %></a>
                        <% } %>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<?php if($welcome == 'true') : ?>
    <script type="application/javascript">
        sessionStorage.welcome_channel = 1;
    </script>
<?php endif; ?>
<?= $this->render('@frontend/modules/netwrk/views/default/partial/landing_channel_welcome');?>