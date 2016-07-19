<?php
    use yii\helpers\Url;
    use yii\web\Cookie;
    $cookies = Yii::$app->request->cookies;
?>
<script type="text/javascript">
    <?php if (isset($cookies["nw_popover_chat_topic_title"])) {?>
        var popoverChatTopicTitle = '',
            chatTopicTitlePopover = '';
    <?php } else { ?>
        var popoverChatTopicTitle = 'popover-chat-topic-title',
            chatTopicTitlePopover = "Checkout this line's highlights in its channel";
    <?php } ?>
</script>
<script id="popup_chat" type="text/x-underscore-template">
    <div class='popup_chat_modal'>
        <div class="popup-box chat-popup" id="popup-chat-<%= post_id %>" data-id ='<%= post_id %>' data-chat-type='<%= chat_type %>'>
            <% if ((chat_type == 0)){ %>
                <div class="popup-head">
            <% } else { %>
                <div class="popup-head chat-discussion">
            <% } %>
                <% if ((chat_type == 0)){ %>
                    <div class="popup-head-left">
                        <span class='popup-title-avatar'><img  src='<%= post_avatar %>' /></span>
                        <span class='popup-title-username'><%= post_name %></span>
                    </div>
                <% }else{ %>
                    <div class="popup-head-left">
                        <span class='popup-title-name'><%= post_name %></span>
                        <span class='popup-title-description popup-topic-trigger <%= popoverChatTopicTitle %>'
                            data-city-name='<%= city_name %>'
                            data-city='<%= city %>'
                            data-value='<%= topic_id %>'
                            data-template='<div class="popover info-popover chat-topic-title-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_chat_topic_title" data-wrapper="popover-chat-topic-title">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
                            data-placement="top"
                            data-content="<%= chatTopicTitlePopover %>">
                            <span class="topic-title">
                                <%= post_description %>
                            </span>
                        </span>
                    </div>
                <% } %>
                <div class="popup-head-right">
                    <a class='minimize-btn'><i class='fa fa-minus 2x'></i></a>
                    <a href="javascript:PopupChat.ClosePopup(<%= post_id %>);"><i class='fa fa-times 2x'></i></a>
                </div>
                <div style="clear: both"></div>
            </div>
            <% if (chat_type == 1){ %>
                <div class="chat-feedback">
                    <?= $this->render('@frontend/modules/netwrk/views/feedback/view') ?>
                </div>
            <% } %>
            <div class="popup-messages">
                <% if ((chat_type == 0)){ %>
                    <div class='popup_chat_container container_private_chat'>
                <% } else { %>
                    <div class='popup_chat_container'>
                <% } %>
                </div>
            </div>

            <div class="nav_input_message">
             <img src='<?= Url::to("@web/img/icon/ajax-loader.gif")?>' class='loading_image' />
                <div class="send_message input-group no-login">
                    <input type="text" class="form-control" placeholder="You have to log in to chat" disabled="true">
                    <div class="input-group-addon send" id="sizing-addon2">Login</div>
                </div>
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
            </div>
        </div>
    </div>
</script>

<script id="message_chat" type="text/x-underscore-template">
    <% if ((msg.id == UserLogin)){ %>
        <div class="message_send message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>" data-post-id="<%= msg.post_id %>" data-user-id="<%= msg.id %>">
    <% }else{ %>
        <div class="message_receiver message" data-img="<?#= Url::to('@web/img/icon/timehdpi.png'); ?>" data-post-id="<%= msg.post_id %>" data-user-id="<%= msg.id %>">
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
                        <div class="user_thumbnail">
                            <div class="avatar">
                                <img src="<%= baseurl %><%=  msg.avatar %>">
                            </div>
                        </div>
                        <div class="feedback-line"></div>
                        <div class="feedback feedback-trigger" data-parent="#popup-chat-<%= msg.post_id %>" data-object="ws_message" data-id="<%= msg.msg_id %>">F</div>
                        <p class="time"><%= msg.created_at %></p>
                    </div>
                    <div class="content_message">
                        <% if(msg.msg_type == 1) { %>
                            <p class="content"><%= msg.msg %></p>
                        <% }else if(msg.msg_type == 2) { %>
                            <a class='img_chat_style' href='<?= Url::to("@web/img/uploads/") ?><%= msg.post_id %>/<%= msg.msg %>' target='_blank'><img src='<?= Url::to("@web/img/uploads/") ?><%= msg.post_id %>/thumbnails/thumbnail_<%= msg.msg %>'/></a>
                        <% } else { %>
                            <a class='file-uploaded-link' href='<?= Url::to("@web/files/uploads/") ?><%= msg.post_id %>/<%= msg.msg %>' target='_blank'><%= msg.msg %></a>
                        <% } %>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script id="list_emoji" type="text/x-underscore-template">
    <% _.each(emoji,function(i,e){ %>
        <li data-value="<%= i %>" data-toggle="tooltip" title="<%= i %>"><%= i %></li>
    <% })%>
</script>
