<?php
	use yii\helpers\Url;
	use yii\web\Cookie;
	$cookies = Yii::$app->request->cookies;
?>
<div id='netwrkNews' class='netwrk-news left-slider'>
	<div class="header-wrapper text-center"></div>
	<div class="tab-wrapper">
		<!--<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="col-xs-4 active"><a href="#yourNetwrk" aria-controls="yourNetwrk" role="tab" data-toggle="tab"><span>Your Netwrk</span></a></li>
			<li role="presentation" class="col-xs-4"><a href="#mostActive" aria-controls="mostActive" role="tab" data-toggle="tab"><span>Most Active</span></a></li>
			<li role="presentation" class="col-xs-4"><a href="#publicCenters" aria-controls="publicCenters" role="tab" data-toggle="tab"><span>Public Centers</span></a></li>
		</ul>-->
		<div class="tab-content content-wrapper">

		</div>
	</div>
</div>
<script id="netwrk_news" type="text/x-underscore-template">

	<% if(!_.isEmpty(landing.chatFeeds)) {%>
		<section class="chat-section-wrapper">
			<!--<div class="chat-section"> Chats near you </div>-->
			<% if(!_.isEmpty(landing.chatFeeds)) {%>
				<% _.each(landing.chatFeeds, function(e, key){ %>
					<% if ((e.is_post == 1)){ %>
						<div class="chat-feed-row chat-feed-post" data-user="<%= e.user_id %>" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-topic='<%= e.topic_id %>'>
							<div class="avatar-poster"><div class="image"><img src="<%= e.photo %>"></div></div>
							<div class="chat-feed-content">
								<div class='post'>
									<div class="user-info">
										<div>
											<span class="post-create-by"><%= e.posted_by %></span>
											<span class='appear-day'>
												<% if ((e.appear_day == 'Now')){ %>
												  Just Now
												<% }else{ %>
												  <%= e.appear_day %>
												<% } %>
											</span>
										</div>
										<div>
											<% if(!_.isEmpty(e.location)) {%>
												<span class='post-location'><%= e.location %></span>
											<% } %>
										</div>
									</div>
									<div class="hide post-title"><%= e.title %></div>
									<% if(!_.isEmpty(e.msg_type) && e.msg_type == 2) {%>
										<div class="post-image-wrapper"><img src='<?= Url::to("@web/img/uploads/") ?><%= e.id %>/thumbnails/thumbnail_<%= e.msg %>'/></div>
									<% }else{ %>
										<div class='post-msg'><%= e.msg %></div>
									<% } %>
								</div>
							</div>
						</div>
					<% } %>

				<% }); %>
			<% } %>
		</section>
	<% } %>

	<% if(!_.isEmpty(landing.feeds)) {%>
	<section class="feed-section-wrapper">
		<!--<div class="feed-section"> Feeds near you </div>-->
		<% _.each(landing.feeds, function(city_feed, key){ %>
			<% _.each(city_feed, function(e, key){ %>
				<% if ((e.is_post == 1)){ %>
					<div class="feed-row feed-post" data-user="<%= e.user_id %>" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-topic='<%= e.topic_id %>'>
						<!--<div class="avatar-poster"><div class="image"><img src="<%= e.photo %>"></div></div>-->
						<div class="feed-content">
							<div class='post'>
								<div class="user-info">
									<span class='post-create-by'><%= e.posted_by %></span>
									<% if(!_.isEmpty(e.location)) {%>
										<span class='post-location'><i class="fa fa-map-marker"></i><%= e.location %></span>
									<% } %>
									<span class='appear-day'>
										<% if ((e.appear_day == 'Now')){ %>
										  Just Now
										<% }else{ %>
										  <%= e.appear_day %>
										<% } %>
									</span>
								</div>
								<!--<div class='post-title'><%= e.title %></div>-->
								<% if(!_.isEmpty(e.msg_type) && e.msg_type == 2) {%>
									<div class="post-image-wrapper"><img src='<?= Url::to("@web/img/uploads/") ?><%= e.id %>/thumbnails/thumbnail_<%= e.msg %>'/></div>
								<% }else{ %>
									<div class='post-title'><%= e.msg %></div>
								<% } %>
							</div>
						</div>
					</div>
				<% }else{ %>
					<div class="feed-row feed-topic fav-community-topic" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-city-name='<%= e.city_name %>'>
						<div class="feed-content">
							<span class='topic-title'><%= e.title %></span>
							<span class='topic-create-by'>Channel created by: <%= e.created_by %></span>
							<span class='appear-day'>
								<% if ((e.appear_day == 'Now')){ %>
									Just Now
								<% }else{ %>
									<%= e.appear_day %> ago
								<% } %>
							</span>
						</div>
					</div>
				<% } %>
			<% }); %>
		<% }); %>
	</section>
	<% } %>
	<% if(!_.isEmpty(landing.twitterFeeds)) {%>
		<% if(!_.isEmpty(landing.twitterFeeds.statuses)) {%>
			<!--<div class="twitter-section"> Tweets near you </div>-->
		<% } %>
		<% _.each(landing.twitterFeeds.statuses, function(tweet, key){ %>
		<div class="tweet-feed-row  tweet-feed-post">
			<div class="avatar-poster"><div class="image"><img src="<%= tweet.user.profile_image_url_https %>"></div></div>
			<div class="feed-content">
				<div class='post'>
					<div class="user-info">
						<span class='post-create-by'><%= tweet.user.name %></span>
						<span class="user-mention">@<%= tweet.user.screen_name %></span>
					</div>
					<div class='post-title'><%= tweet.text %></div>
					<div class="api-logo">
						<i class="fa fa-twitter"></i>
					</div>
				</div>
			</div>
		</div>
		<% }); %>
	<% } %>

	<% if(!_.isEmpty(landing.jobFeeds)) {%>
		<!--<div class="jobs-section"> Jobs near you </div>-->
			<% if(!_.isEmpty(landing.jobFeeds.results)) {%>
				<% _.each(landing.jobFeeds.results, function(item, key){ %>
					<div class="job-feed-row  job-feed-post">
						<!--<div class="avatar-poster"><div class="image"><img src=""></div></div>-->
						<div class="feed-content">
							<div class='post'>
								<div class='post-title'>
									<span class="job-title"><%= item.jobtitle %></span>
								</div>
								<div class="company-info"><span class="company"><%= item.company %></span> - <span class="location"><%= item.formattedLocation %></span></div>
								<div><span class="snippet post-content"><%= item.snippet %></span></div>
								<div class="text-center"><a class="job-url view-more" href="<%= item.url %>" target="_blank">View more <i class="fa fa-external-link "></i></a></div>
							</div>
						</div>
					</div>
				<% }); %>
			<% } %>
	<% } %>
</script>
<script id="netwrk_header" type="text/x-underscore-template">
	<div class="btn-area-talk" data-topic="<%= landing.topic_id %>" data-city="<%= landing.city_id %>"
		 data-value="<%= landing.post_id %>" data-user="<%= landing.user_id %>"
		 data-title="<%= landing.title %>" data-content="<%= landing.post_content %>">
		<img src="<?= Url::to('@web/img/icon/netwrk-text.png'); ?>" alt="Netwrk"/> <span>news</span>
	</div>
</script>

