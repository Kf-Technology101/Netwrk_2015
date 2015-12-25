<?php use yii\helpers\Url;?>
<div class="result-search">
	<div class="result">

	</div>

	<p class="notice">
		Not what your looking for? Bummer,try again with more detail or make it yourself
	</p>
</div>
<script id="list_result" type="text/x-underscore-template">
	<% if(result.search_local == 0 && result.search_global == 0){ %>
		<p class="no-result">
			There is no matching result
		</p>
	<% }else{ %>
		<div class="location" id="local">
			<div class="title">Local <span>(with in 50 miles)</span></div>
			<div class="content-result">
				<% if(result.search_local == 0){ %>
					<p class="no-result">
						There is no matching result
					</p>
				<% }else{ %>
					<div class="post-result">
						<% _.each(result.local.post,function(e){ %>
							<div class="post-item" data-post="<%= e.id %>">
								<div class="thumb"><img src="<?= Url::to('@web/img/icon/no_avatar.jpg') ?>"></div>
								<div class="content-post">
									<p class="title"><%= e.title %></p>
									<p class="container-post"><%= e.content %></p>
								</div>
								<div class="info">
									<p class="date"><%= e.created_at %></p>
									<div class="brillant">
										<p><%= e.brilliant %></p>
									</div>
								</div>
							</div>
						<% }); %>
					</div>
					<div class="topic-result">
						<% _.each(result.local.topic,function(e){ %>
							<div class="topic-item" data-topic="<%= e.id %>">
								<p class="topic-name"><%= e.title %></p>
								<div class="count-post">
									<p><%= e.post %><i class="fa fa-file-text"></i></p>
								</div>
								<span class="topic-arrow"><i class="fa fa-angle-right"></i></span>
							</div>
						<% }); %>
					</div>
					<div class="netwrk-result">
						<% _.each(result.local.netwrk,function(e){ %>
							<div class="netwrk-item">
								<p class="netwrk-name"><%= e.zipcode %></p>
								<span class="netwrk-arrow"><i class="fa fa-angle-right"></i></span>
							</div>
						<% }); %>
					</div>
				<% } %>
			</div>
		</div>
		<div class="location" id="global">
			<div class="title">Global</div>
			<div class="content-result">
				<% if(result.search_global == 0){ %>
					<p class="no-result">
						There is no matching result
					</p>
				<% }else{ %>
					<div class="post-result">
						<% _.each(result.global.post,function(e){ %>
							<div class="post-item">
								<div class="thumb"><img src="<?= Url::to('@web/img/icon/no_avatar.jpg') ?>"></div>
								<div class="content-post">
									<p class="title"><%= e.title %></p>
									<p class="container-post"><%= e.content %></p>
								</div>
								<div class="info">
									<p class="date"><%= e.created_at %></p>
									<div class="brillant">
										<p><%= e.brilliant %></p>
									</div>
								</div>
							</div>
						<% }); %>
					</div>
					<div class="topic-result">
						<% _.each(result.global.topic,function(e){ %>
							<div class="topic-item" data-topic="<%= e.id %>">
								<p class="topic-name"><%= e.title %></p>
								<div class="count-post">
									<p><%= e.post %><i class="fa fa-file-text"></i></p>
								</div>
								<span class="topic-arrow"><i class="fa fa-angle-right"></i></span>
							</div>
						<% }); %>
					</div>
					<div class="netwrk-result">
						<% _.each(result.global.netwrk,function(e){ %>
							<div class="netwrk-item">
								<p class="netwrk-name"><%= e.zipcode %></p>
								<span class="netwrk-arrow"><i class="fa fa-angle-right"></i></span>
							</div>
						<% }); %>
					</div>
				<% } %>
			</div>
		</div>
	<% } %>
</script>