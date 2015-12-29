<?php use yii\helpers\Url; ?>
<script id="popup_chat" type="text/x-underscore-template">
    <div id='popup_chat_modal'>
        <div class="popup-box chat-popup" id="popup-chat-<%= post_id %>" data-id ='<%= post_id %>'>
            <div class="popup-head">
                <div class="popup-head-left"><%= post_id %></div>
                <div class="popup-head-right">
                    <a href="javascript:PopupChat.ClosePopup(<%= post_id %>);">&#10005;</a>
                </div>
                <div style="clear: both"></div>
            </div>
            <div class="popup-messages">
                <div class='popup_chat_container'>
                </div>
            </div>

            <div class="nav_input_message">
                <?php if(Yii::$app->user->isGuest){?>
                    <div class="send_message input-group">
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
    </div>
</script>

<script id="message_chat" type="text/x-underscore-template">
    <% if ((msg.id == UserLogin)){ %>
        <div class="message_send message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
    <% }else{ %>
        <div class="message_receiver message" data-img="<?#= Url::to('@web/img/icon/timehdpi.png'); ?>">
    <% } %>
        <div class="user_thumbnail">
            <div class="avatar">
                <img src="<%= baseurl %><%=  msg.avatar %>">
            </div>
        </div>
        <div class="content_message">
        <% if(msg.msg_type == 1) { %>
            <p class="content"><%= msg.msg %></p>
        <% }else if(msg.msg_type == 2) { %>
            <a class='img_chat_style' href='<?= Url::to("@web/img/uploads/") ?><%= msg.msg %>' target='_blank'><img src='<?= Url::to("@web/img/uploads/") ?><%= msg.msg %>'/></a>
        <% } else { %>
            <a class='file-uploaded-link' href='<?= Url::to("@web/files/uploads/") ?><%= msg.msg %>' target='_blank'><%= msg.msg %></a>
        <% } %>
        </div>
        <p class="time"><%= msg.created_at %></p>
    </div>
</script>

<script id="list_emoji" type="text/x-underscore-template">
    <% _.each(emoji,function(i,e){ %>
        <li data-value="<%= i %>" data-toggle="tooltip" title="<%= i %>"><%= i %></li>
    <% })%>
</script>
