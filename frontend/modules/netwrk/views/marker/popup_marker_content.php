<?php use yii\helpers\Url; ?>
<script id="maker_popup" type="text/x-underscore-template" >
	<div id="iw-container" >
		<div class="top-post">
		</div>
		<div class="iw-title">
			<span class="toppost">Top Post</span>
			<a class="info_zipcode" data-city="<%= data.id %>" onclick="Map.eventOnClickZipcode(<%= data.id %>)">
			<span class="zipcode"><%= data.zip_code %> </span></a>
		</div>
		<div class="iw-content">
			<div class="iw-subTitle"><span class="post-title">#<%= data.post.name_post %></span></div>
			<p><%= data.post.content %></p>
		</div>
  	<div class="iw-bottom-gradient"></div>
	</div>
</script>