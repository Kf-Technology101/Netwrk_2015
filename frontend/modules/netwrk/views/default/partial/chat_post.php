<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_chat_post'>
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
				<div class="container_post_chat"></div>
			</div>
			<div class="modal-footer">
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
			</div>
		</div>
	</div>
	<script id="list_emoji" type="text/x-underscore-template">
		<% _.each(emoji,function(i,e){ %>
			<li data-value="<%= e %>"><%= e %></li>
		<% })%>
	</script>
	<script id="chatpost_name" type="text/x-underscore-template">
		<span><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a></span>
		<span><i class="fa fa-angle-right"></i><%= name.topic_name%></span>
		<span><i class="fa fa-angle-right"></i><%= name.post_name %></span>
	</script>
	<script id="message_chat" type="text/x-underscore-template">
		<% if (msg.user_current){ %>
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
            	<img src='<?= Url::to("@web/img/uploads/") ?><%= msg.msg %>' class='img_chat_style'/>
        	<% } else { %>
        		<a href='<?= Url::to("@web/files/uploads/") ?><%= msg.msg %>' target='_blank'><%= msg.msg %></a>
    		<% } %>
	            <p class="time"><%= msg.created_at %></p>
	        </div>
	    </div>
	</script>
</div>
