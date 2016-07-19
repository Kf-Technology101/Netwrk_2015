<?php
	use yii\helpers\Url;
	use yii\web\Cookie;
	$cookies = Yii::$app->request->cookies;
?>
<div id='chat_inbox' class='chat-inbox' >
	<!-- Nav tabs -->
	<ul class="nav nav-tabs chat-inbox-tab" role="tablist">
		<li role="presentation" class=" col-xs-6 chat-private-btn"><a href="#chat_private_tab" aria-controls="chat_private_tab" role="tab" data-toggle="tab"><span>Messages</span></a></li>
		<li role="presentation" class="active col-xs-6 chat-dicussions-btn"><a href="#chat_discussion_tab" aria-controls="chat_discussion_tab" role="tab" data-toggle="tab"><span>Lines</span></a></li>
	</ul>
	<!--<i id='hide_chat_inbox_btn' class="fa fa-times"></i>-->
	<!-- Tab panes -->
	<div class="tab-content chat-inbox-content">
		<div role="tabpanel" class="tab-pane " id="chat_private_tab">
			<div id="chat_private" class="chat-lines-wrapper">
				<ul>

				</ul>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane active" id="chat_discussion_tab">
			<?php
				if (isset($cookies["nw_popover_chat"])) {
					$popover_class = '';
					$party_lines_popover = '';
					$lines_popover = '';
				} else {
					$popover_class = 'popover-chat';
					$party_lines_popover = 'See your community news';
					$lines_popover = 'Interact with your community';
				}
			?>
			<div class="panel-group" id="accordion">
				<div class="panel panel-default" id="panelLocalPartyLines">
					<div class="panel-heading">
						<a data-toggle="collapse" data-target="#collapseLocalPartyLines"
						   href="javascript:" class="<?php echo $popover_class;?>"
						   data-template='<div class="popover info-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
						   data-placement="top" data-content="<?php echo $party_lines_popover;?>">
							<p class="panel-title">Popular public chat lines near you</p>
						</a>
					</div>
					<div id="collapseLocalPartyLines" class="panel-collapse collapse in">
						<div class="panel-body top-post-content party-lines-content">
							<div id="containerLocalPartyLines" class="chat-lines-wrapper local-party-lines">
								<ul>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chat-lines-title <?php echo $popover_class;?>"
				data-template='<div class="popover info-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
				data-placement="top" data-content="<?php echo $lines_popover;?>">Your lines</div>
			<div id="chat_discussion" class="chat-lines-wrapper">
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
					<% if(parseInt(chat_inbox.discussion_notification_count) > 0){ %>
					<span class='notify-chat-inbox'><%= parseInt(chat_inbox.discussion_notification_count) %></span>
					<% }else{ %>
					<span class='notify-chat-inbox disable'></span>
					<% } %>
					<div class='description-chat-inbox'><%= chat_inbox.topic_name %></div>
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
					<span class='notify-chat-inbox disable'>3</span>
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
