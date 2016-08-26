<?php
use yii\helpers\Url;
use yii\web\Cookie;
$cookies = Yii::$app->request->cookies;
?>
<div id='areaNews' class='area-news'>
    <div class="header-wrapper text-center"></div>
    <div class="tab-wrapper">
        <!--<ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="col-xs-4 active"><a href="#yourNetwrk" aria-controls="yourNetwrk" role="tab" data-toggle="tab"><span>Your Netwrk</span></a></li>
            <li role="presentation" class="col-xs-4"><a href="#mostActive" aria-controls="mostActive" role="tab" data-toggle="tab"><span>Most Active</span></a></li>
            <li role="presentation" class="col-xs-4"><a href="#publicCenters" aria-controls="publicCenters" role="tab" data-toggle="tab"><span>Public Centers</span></a></li>
        </ul>-->
        <div class="tab-content content-wrapper">
                content
        </div>
    </div>
</div>
<script id="area_news" type="text/x-underscore-template">
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
</script>