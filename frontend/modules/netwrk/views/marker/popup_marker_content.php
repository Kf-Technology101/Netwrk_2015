<?php use yii\helpers\Url; ?>
<script id="maker_popup" type="text/x-underscore-template" >
	<div class="container-popup" >
		<% if(marker.user) { %>
			<div class="top-post">
				<div class="user">
					<div class="avatar">
						<div class="image">
							<img src="<%= marker.user.avatar %>">
						</div>
					</div>
					<div class="information">
						<p class="username"><%= marker.user.username %></p>
						<p class="address"><%= marker.user.username %>,<%= marker.user.place %>,USA</p>
					</div>
				</div>
				<div class="brilliant"><p><%= marker.post.brilliant %></p></div>
				<div class="post">
					<p class="name"><%= marker.post.name_post %></p>
					<p class="content"><%= marker.post.content %></p>
				</div>
			</div>
			<div class="top-topic">
				<p class="title">Top Topics</p>
				<% _.each(marker.topic,function(e){%>
					<p class="name-topic"><%= e.name %></p>
				<% }) %>
			</div>
			<div class="top-post-trending">
				<p class="title">Trending</p>
				<% _.each(marker.trending_post,function(e){ %>
					<div class="item-post">
						<p class="name-post"><%= e.post_name %></p>
						<p class="num-post"><%= e.post_trending %> Posts</p>
					</div>
				<% })%>
			</div>
		<% }else{ %>
			<p class="notice">Be the first one to create Topic</p>
			<div class="create-topic">Create Now</div>
		<% } %>

	</div>
</script>