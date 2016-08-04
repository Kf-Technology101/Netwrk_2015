<?php use yii\helpers\Url; ?>
<script id="maker_popup" type="text/x-underscore-template" >
	<div class="container-popup" onmouseleave="Map.mouseOutsideInfoWindow();" onmouseenter="Map.mouseInsideInfoWindow();">
		<% if(marker.user) { %>
			<div class="community-header text-center">
				<h3>Local community center</h3>
			</div>
			<div class="top-post">
				<div class="user">
					<div class="avatar">
						<div class="image">
							<img src="<%= marker.user.avatar %>">
						</div>
					</div>
					<div class="information">
						<p class="username"><%= marker.user.username %></p>
						<p class="address">
							<% if(marker.user.work){ %>
								<%= marker.user.work + ',' %>
							<% } %>
							<% if(marker.user.place){ %>
								<%= marker.user.place + ',' %>
							<% } %>
							USA
						</p>
					</div>
				</div>
				<div class="brilliant"><p><%= marker.post.brilliant %></p></div>
				<div class="post">
					<p class="name"><%= marker.post.name_post %></p>
					<p class="content"><%= marker.post.content %></p>
				</div>
			</div>
			<div class="top-topic">
				<p class="title">Top Channels</p>
				<% _.each(marker.topic,function(e,i){%>
					<p class="name-topic" data-index="<%= i %>"><%= e.name %></p>
				<% }) %>
			</div>
			<div class="top-post-trending">
				<p class="title">Trending</p>
				<% _.each(marker.trending_hashtag,function(e){ %>
					<div class="item-post">
						<p class="name-post" data-value="<%= e.hashtag_id %>"
						   data-topic_id="<%= e.topic_id %>"
						   data-topic_title="<%= e.topic_title %>"
						   data-city_id="<%= marker.id %>"
						   data-city_zipcode="<%= marker.zip_code %>">
							<%= e.hashtag_name %></p>
						<p class="num-post"><%= e.hashtag_post %> Posts</p>
					</div>
				<% })%>
			</div>
		<% }else{ %>
			<div class="no-topic">
				<p class="notice">Be the first one to create Channel</p>
				<div class="create-topic">Create Now</div>
			</div>
		<% } %>

	</div>
</script>