<?php use yii\helpers\Url;?>
<div class="result-search">
	<div class="result">

	</div>

	<p class="notice">
		Not what your looking for? Bummer, try again with more detail or make it yourself
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
							<div class="post-item item-result" data-post="<%= e.id %>">
								<div class="thumb"><img src="<%= e.thumb %>"></div>
								<div class="content-post">
									<p class="title title-result"><%= e.title %></p>
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
							<div class="topic-item item-result" data-topic="<%= e.id %>" data-city-id="<%= e.city_id %>" data-city-name="<%= e.city_name %>">
								<p class="topic-name title-result"><%= e.title %></p>
								<div class="count-post">
									<p><%= e.post %><i class="fa fa-file-text"></i></p>
								</div>
								<span class="topic-arrow"><i class="fa fa-angle-right"></i></span>
							</div>
						<% }); %>
					</div>
					<div class="netwrk-result">
						<% _.each(result.local.netwrk,function(e){ %>
							<div class="netwrk-item item-result" data-netwrk="<%= e.id %>">
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
							<div class="post-item item-result" data-post="<%= e.id %>">
								<div class="thumb"><img src="<%= e.thumb %>"></div>
								<div class="content-post">
									<p class="title title-result"><%= e.title %></p>
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
							<div class="topic-item item-result" data-topic="<%= e.id %>" data-city-id="<%= e.city_id %>" data-city-name="<%= e.city_name %>">
								<p class="topic-name title-result"><%= e.title %></p>
								<div class="count-post">
									<p><%= e.post %><i class="fa fa-file-text"></i></p>
								</div>
								<span class="topic-arrow"><i class="fa fa-angle-right"></i></span>
							</div>
						<% }); %>
					</div>
					<div class="netwrk-result">
						<% _.each(result.global.netwrk,function(e){ %>
							<div class="netwrk-item item-result" data-netwrk="<%= e.id %>">
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