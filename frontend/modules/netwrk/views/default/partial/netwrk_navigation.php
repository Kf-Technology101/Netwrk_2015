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
		<!--<div class="title">Netwrk</div>-->
		<div class="your-netwrks">
			<div class="favorites-netwrks-wrapper">

			</div>
		</div>
	</div>

</div>
<script id="favorites-netwrks" type="text/x-underscore-template">
	<% if(!_.isEmpty(items.data)) {%>
		<% _.each(items.data,function(cities, key){ %>
			<section class="group">
				<div class="city-name"><%= key %></div>
				<% _.each(cities, function(item, i){ %>
				<section class="item community-modal-trigger <% if(item.city_zipcode == item.selected_zipcode) {%> active <% } %>"
						 data-lat="<%= item.lat %>"
						 data-lng="<%= item.lng %>"
						 data-zip_code="<%= item.city_zipcode %>"
						 data-city-id="<%= item.city_id %>"
						 data-city_name="<%= item.city_name %>">
					<div class="home-community-icon">
						<%= key.substr(0,1) %>
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

