<?php
	use yii\helpers\Url;
	use yii\web\Cookie;
	$cookies = Yii::$app->request->cookies;
?>
<div id='netwrkNavigation' class='netwrk-navigation'>
	<!--<div class="netwrk-news-trigger custom-btn btn-netwrk-news">Netwrk News</div>-->
	<!--<div class="most-active-trigger custom-btn btn-most-active">Most Active</div>-->
	<div class="your-netwrk-wrapper">
		<a class="landing-close-trigger" href="javascript:void(0)">
			<img src="/img/icon/netwrk-icon-inactive.png">
		</a>
		<div class="title">Netwrk</div>
		<div class="your-netwrks">
			<div class="favorites-netwrks-wrapper">

			</div>
		</div>
	</div>
	<?php if (Yii::$app->user->isGuest):?>
		<div id="navProfileWrapper" class="login-trigger custom-btn btn-profile">Login</div>
	<?php endif; ?>
</div>
<script id="favorites-netwrks" type="text/x-underscore-template">
	<% if(!_.isEmpty(items.data)) {%>
		<% _.each(items.data,function(cities, key){ %>
			<section class="group">
				<div class="city-name"><%= key %></div>
				<% _.each(cities, function(item, key){ %>
				<section class="item community-modal-trigger"
						 data-lat="<%= item.lat %>"
						 data-lng="<%= item.lng %>"
						 data-zip_code="<%= item.city_zipcode %>"
						 data-city-id="<%= item.city_id %>">
					<div class="home-community-icon">
						<% if(item.city_office_type == "university") {%>
							<i class="fa fa-graduation-cap"></i>
						<% } else if(item.city_office_type == "government") { %>
							<i class="fa fa-institution"></i>
						<% } else { %>
							<i class="fa fa-home"></i>
						<% } %>
					</div>
					<div class="zipcode"><%= item.city_zipcode %></div>
				</section>
				<% }); %>
			</section>
		<% }); %>
	<% } else { %>
		<p class="join-home-btn" id="join-home-btn">Join your area</p>
	<% } %>
</script>

