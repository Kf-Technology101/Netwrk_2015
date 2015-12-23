<?php use yii\helpers\Url; ?>
<div id='chat_inbox' class='chat-inbox' >
	<!-- Nav tabs -->
	<ul class="nav nav-tabs chat-inbox-tab" role="tablist">
		<li role="presentation" class=" active col-xs-6 chat-private-btn"><a href="#chat_private" aria-controls="chat_private" role="tab" data-toggle="tab"><span>Chats</span></a></li>
		<li role="presentation" class=" col-xs-6 chat-dicussions-btn"><a href="#chat_discussion" aria-controls="chat_discussion" role="tab" data-toggle="tab"><span>Discussions</span></a></li>
	</ul>
	<i id='hide_chat_inbox_btn' class="fa fa-times"></i>
	<!-- Tab panes -->
	<div class="tab-content chat-inbox-content">
		<div role="tabpanel" class="tab-pane active" id="chat_private">
			<div id="container_ul_chat_list">
				<ul>

				</ul>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane " id="chat_discussion">
			<div id="container_ul_chat_list">
				<ul>
				</ul>
			</div>
		</div>
	</div>
</div>


<script id="chat_inbox_list" type="text/x-underscore-template" >
	<% _.each(chat_inbox_list,function(chat_inbox){ %>
		<li>
			<div class='chat-post-id' data-post='<%= chat_inbox.id %>'>
				<span class='avatar-user'>
					<img class='img_avatar' src='<?= Url::to("@web/") ?><%= chat_inbox.avatar %>' />
				</span>
				<div class='title-description-user'>
					<div class='title-chat-inbox'><%= chat_inbox.title %></div>
					<div class='description-chat-inbox'><%= chat_inbox.content %></div>
				</div>
				<span class='time-chat-inbox'><i class='fa fa-clock-o'></i> <%= chat_inbox.update_at %></span>
				<i class='fa fa-2x fa-angle-right'></i>
			</div>
			<input type='hidden' value='<%= chat_inbox.topic_id %>' name='topic_id' />
			<input type='hidden' value='<%= chat_inbox.topic_name %>' name='topic_name'/>
			<input type='hidden' value='<%= chat_inbox.city_id %>' name='city_id' />
			<input type='hidden' value='<%= chat_inbox.city_name %>' name='city_name'/>
		</li>

		<% }); %>
	</script>

<script id="chat_private_list" type="text/x-underscore-template" >
	<% _.each(chat_private_list,function(chat_inbox){ %>
		<li>
			<div class='chat-post-id' data-user='<%= chat_inbox.user_id_guest %>' data-post='<%= chat_inbox.post_id %>'>
				<span class='avatar-user'>
					<img class='img_avatar' src='<?= Url::to("@web/") ?><%= chat_inbox.avatar %>' />
				</span>
				<div class='title-description-user'>
					<div class='title-chat-inbox'><%= chat_inbox.user_id_guest_first_name + ' '+ chat_inbox.user_id_guest_last_name %></div>
					<span class='notify-chat-inbox'>3</span>
					<div class='description-chat-inbox'><%= chat_inbox.content %></div>
				</div>
				<span class='time-chat-inbox'><i class='fa fa-clock-o'></i> <%= chat_inbox.updated_at %></span>
				<i class='fa fa-2x fa-angle-right'></i>
			</div>
			<input type='hidden' value='<%= chat_inbox.topic_id %>' name='topic_id' />
			<input type='hidden' value='<%= chat_inbox.topic_name %>' name='topic_name'/>
			<input type='hidden' value='<%= chat_inbox.city_id %>' name='city_id' />
			<input type='hidden' value='<%= chat_inbox.city_name %>' name='city_name'/>
		</li>

		<% }); %>
	</script>
