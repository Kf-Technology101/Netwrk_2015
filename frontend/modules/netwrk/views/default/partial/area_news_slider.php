<?php
use yii\helpers\Url;
use yii\web\Cookie;
$cookies = Yii::$app->request->cookies;
?>
<div id='areaNews' class='area-news'>
    <div class="header clearfix">
        <div class="title_page left-section">

        </div>
        <div class="middle-section">
            <i class="fa fa-align-justify"></i>
        </div>
        <div class="right-section">
            <div class="feedback-line"></div>
            <span class="title">Welcome</span>
        </div>
    </div>
    <div class="header-wrap">

    </div>
    <div class="tab-wrapper">
        <!--<ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="col-xs-4 active"><a href="#yourNetwrk" aria-controls="yourNetwrk" role="tab" data-toggle="tab"><span>Your Netwrk</span></a></li>
            <li role="presentation" class="col-xs-4"><a href="#mostActive" aria-controls="mostActive" role="tab" data-toggle="tab"><span>Most Active</span></a></li>
            <li role="presentation" class="col-xs-4"><a href="#publicCenters" aria-controls="publicCenters" role="tab" data-toggle="tab"><span>Public Centers</span></a></li>
        </ul>-->
        <div class="tab-content content-wrapper">
            <div id="area_tab_feed" class="tab">
                <p class="no-data">There is no data available yet</p>
            </div>
        </div>
    </div>
</div>
<script id="city_name" type="text/x-underscore-template">
    <span class="title">
        <% if(office_type == 'university') { %>
            <i class="fa fa-lg fa-university"></i>
            Idea area news
        <% } else if(office_type == 'government') { %>
            <i class="fa fa-lg fa-institution"></i>
            Gov - Problem solving area news
        <% } else { %>
            <i class="fa fa-lg fa-home"></i>
            Area HQ news
        <% } %>
    </span>
</script>
<script id="area_feed_list" type="text/x-underscore-template" >

    <% if(feed.feed.length > 0) { %>
    <div class="top-feed">
        <div class="top-header">
            <% if(feed.office_type == 'university') { %>
            <p class="lp-title">Welcome to your local Idea center</p>
            <p class="lp-description">Idea Center News</p>
            <% } else if(feed.office_type == 'government') { %>
            <p class="lp-title">Welcome to your local solution center</p>
            <p class="lp-description">Solution Center News</p>
            <% } else { %>
            <p class="lp-title">Welcome to your local Community Center</p>
            <p class="lp-description">Local News</p>
            <% } %>
        </div>
        <div class="top-feed-content"></div>
    </div>
    <% } %>
</script>
<script id="area_top_feed" type="text/x-underscore-template">
    <%
    _.each(feed.feed,function(e,i){ %>
    <% if ((e.is_post == 1)){ %>
    <div class="feed-row feed-post" data-user="<%= e.user_id %>" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-topic='<%= e.topic_id %>'>
        <div class="avatar-poster"><div class="image"><img src="<%= e.photo %>"></div></div>
        <div class="feed-content">
            <div class='post'>
                <div class='post-title'><%= e.title %></div>
                <div class='post-content'><%= e.content %></div>
            </div>
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
    <% }else{ %>
    <div class="feed-row feed-topic" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-city-name='<%= e.city_name %>'>
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
    <%
    });
    %>
</script>