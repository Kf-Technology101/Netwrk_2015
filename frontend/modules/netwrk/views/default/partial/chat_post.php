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
							<span><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a></span>
							<span><i class="fa fa-angle-right"></i>Dog</span>
							<span><i class="fa fa-angle-right"></i> General Dogs</span>
						</span>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="message_send message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
					<div class="user_thumbnail">
						<div class="avatar">
							<img src="">
						</div>
					</div>
					<div class="content_message">
						<p>Description of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word Help</p>
						<p class="time">20:30</p>
					</div>		
				</div>
				<div class="message_receiver message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
					<div class="user_thumbnail">
						<div class="avatar">
							<img src="">
						</div>
					</div>
					<div class="content_message">
						<p>Description of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word Help</p>
						<p class="time">20:30</p>
					</div>	
				</div>
			</div>
			<div class="modal-footer">
				<div class="send_message input-group">
					<textarea type="text" class="form-control" placeholder="Type message here..."></textarea>
					<div class="input-group-addon paper"><i class="fa fa-paperclip"></i></div>
					<div class="input-group-addon emoji"><i class="fa fa-smile-o"></i></div>
					<div class="input-group-addon send" id="sizing-addon2">Send</div>
				</div>
			</div>
		</div>
	</div>
	<script id="city_name" type="text/x-underscore-template">
		<span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a><%= city %></span>
	</script>
	<script id="topic_list" type="text/x-underscore-template" >
		  <% _.each(topices,function(topic){ %>
		      <div class="item" data-item="<%= topic.id %>">
		        <div class="topic_post">
		            <div class="name_topic">
		                <p><%= topic.title %></p>
		            </div>
		        </div> 
		        <div class="num_count_duration">
		            <div class="most_post">
		                <p><i class="fa fa-clock-o"></i><%= topic.created_at%></p>
		            </div>   
		        </div> 
		        <div class="num_count">
		            <div class="most_post">
		                <p><i class="fa fa-file-text"></i><%= topic.post_count%></p>
		            </div>   
		        </div> 
		        <div class="num_count">
		            <div class="most_post">
		                <p><i class="fa fa-eye"></i><%= topic.view_count%></p>
		            </div>   
		        </div>
		    </div>
		<% }); %>  
	</script>
</div>
