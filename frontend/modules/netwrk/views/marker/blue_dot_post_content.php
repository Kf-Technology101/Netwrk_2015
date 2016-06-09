<?php use yii\helpers\Url; ?>
<script id="blue_dot_maker_posts" type="text/x-underscore-template">
	<div class="top-post-brilliant">
		<% _.each(marker.posts,function(e){ %>
			<div class="item-post">
				<p class="name-post" data-value="<%= e.post_id %>"
				   data-type="<%= e.post_type %>"
				   data-name="<%= e.name_post %>"
				   data-content="<%= e.content %>"><%= e.name_post %></p>
				<!--<p class="num-post"><%= e.brilliant %></p>-->
			</div>
		<% })%>
	</div>
</script>