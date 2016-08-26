<?php
	use yii\helpers\Url;
	use yii\web\Cookie;
	$cookies = Yii::$app->request->cookies;
?>
<div id='netwrkNews' class='netwrk-news'>
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
	<% if(!_.isEmpty(landing.feeds)) {%>
		<% _.each(landing.feeds, function(city_feed, key){ %>
			<% _.each(city_feed, function(e, key){ %>
				<% if ((e.is_post == 1)){ %>
					<div class="feed-row feed-post" data-user="<%= e.user_id %>" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-topic='<%= e.topic_id %>'>
						<div class="avatar-poster"><div class="image"><img src="<%= e.photo %>"></div></div>
						<div class="feed-content">
							<div class='post'>
								<div class='post-title'><%= e.title %></div>
								<div class='post-content'><%= e.content %></div>
								<span class='post-create-by'>Posted by: <%= e.posted_by %></span>
								<span class='appear-day'>
									<% if ((e.appear_day == 'Now')){ %>
									  Just Now
									<% }else{ %>
									  <%= e.appear_day %> ago
									<% } %>
								</span>
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
	<% } %>
	<!--<div role="tabpanel" class="tab-pane active" id="yourNetwrk">-->
	<!--</div>
	<div role="tabpanel" class="tab-pane" id="mostActive">
		<%
			var len_post = landing.top_post.length;
			_.each(landing.top_post,function(e,i){
				if(i == len_post - 1){%>
					<div class="post-row" data-value="<%= e.id %>" data-user="<%= e.user_id %>">
				<% }else{ %>
					<div class="post-row" data-value="<%= e.id %>" data-user="<%= e.user_id %>">
				<% } %>
				<div class="avatar"><div class="image"><img src="<%= e.photo %>"></div></div>

				<div class="post">
					<p class="post-title"><%= e.title %></p>
					<div class="post-content"><%= e.content%></div>
				</div>
				<div class="action">
					<div class="chat"><i class="fa fa-comments"></i>Jump in</div>
					<span class="chat feedback-wrapper">
						<div class="feedback-line"></div>
						<div class="feedback">F</div>
					</span>
				</div>
			</div>
			<%
			});
		%>

		<%
			var len_topic = landing.top_post.length;
			_.each(landing.top_topic,function(e,i){
				if(i == len_topic - 1){ %>
					<div class="topic-row last-row" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-city-name="<%= e.city_name %>">
				<% }else{ %>
					<div class="topic-row" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-city-name="<%= e.city_name %>">
				<% } %>
					<p class="topic-title"><%= e.name %></p>
					<div class="post-counter">
						<%= e.post_count %>
						<span class="arrow"><i class="fa fa-angle-right"></i></span>
						<i class="fa fa-file-text"></i>
					</div>
				</div>
				<%
			});
		%>
	</div>
	<div role="tabpanel" class="tab-pane" id="publicCenters">
		<%
			_.each(landing.top_communities,function(e,i){ %>
				<div class="communities-row" data-city="<%= e.city_id %>">
					<div class="com-content">
						<p class="zipcode" ><%= e.zip_code %> - <%= (e.office_name != null) ? e.office_name : e.city_name %></p>
						<p class="subtext">
							<% _.each(e.top_hashtag,function(d,s){ %>
							<span><%=d.hashtag %></span>
							<%})%>
						</p>
					</div>
					<span class="arrow"><i class="fa fa-angle-right"></i></span>
				</div>
				<%
			});
		%>
	</div>-->
</script>
<script id="netwrk_header" type="text/x-underscore-template">
	<div class="btn-area-talk" data-topic="<%= landing.topic_id %>" data-city="<%= landing.city_id %>"
		 data-value="<%= landing.post_id %>" data-user="<%= landing.user_id %>"
		 data-title="<%= landing.title %>" data-content="<%= landing.post_content %>">
		<img src="<?= Url::to('@web/img/icon/netwrk-text.png'); ?>" alt="Netwrk"/> <span>news</span>
	</div>
</script>

