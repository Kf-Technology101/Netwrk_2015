<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_chat_private'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="header">
					<div class="back_page">
						<span><i class="fa fa-arrow-circle-left"></i> Back </span>
					</div>
					<div class="title_page">
						<span class="title">

						</span>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="container_private_chat"></div>
				<img src='<?= Url::to('@web/img/icon/ajax-loader.gif'); ?>' class='loading_image' />
			</div>
			<div class="modal-footer">
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
	<script id="list_emoji" type="text/x-underscore-template">
		<% _.each(emoji,function(i,e){ %>
			<li data-value="<%= i %>" data-toggle="tooltip" title="<%= i %>"><%= i %></li>
		<% })%>
	</script>
	<script id="chatprivate_name" type="text/x-underscore-template">
		<span id='logo_modal_chat'><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></span>
		<span><i class="fa fa-angle-right"></i><%= name.topic_name%></span>
		<span><i class="fa fa-angle-right"></i><%= name.post_name %></span>
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
	            <p class="time"><%= msg.created_at %></p>
	        </div>
	    </div>
	</script>
</div>
