<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_topic'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="header">
            <div class="back_page">
              <span><i class="fa fa-arrow-circle-left"></i> Back </span>
            </div>
            <div class="Favorite-btn-wrap">
            </div>
            <div class="title_page">
            </div>
            <!--<div class="create_topic" id="create_topic">
              <span><i class="fa fa-plus-circle"></i> Create Topic</span>
            </div>-->
             <!-- <div class="create_topic" id="create_group">
                  <span><i class="fa fa-plus-circle"></i> Create Group</span>
              </div>-->
          </div>
          <div class="sidebar">
            <!--<div class="title"></div>-->
            <!--<div class="topics-dropdown dropdown input-group">
                <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <li data-value="recent">Most recent</li>
                  <li data-value="post">Most posts</li>
                  <li data-value="view">Most viewed</li>
                </ul>
            </div>-->
            <!--<div class="groups-dropdown dropdown input-group">
              <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
              <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <li data-value="recent">Most recent</li>
                  <li data-value="post">Most posts</li>
                  <li data-value="view">Most viewed</li>
              </ul>
            </div>-->
            <table class="filter_sidebar">
                <tr>
                    <td class="feed active">Feed</td>
                    <td class="topic">Topics</td>
                    <td class="groups">Groups</td>
                </tr>
            </table>
          </div>
          <div class="tab-header tab-header-topic clearfix hidden">
              <div class="tab-title">
                  <p class="tab-title-text">Topics</p>
                  <div class="topics-dropdown dropdown input-group">
                      <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
                      <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                          <li data-value="recent">Most recent</li>
                          <li data-value="post">Most posts</li>
                          <li data-value="view">Most viewed</li>
                      </ul>
                  </div>
              </div>
              <div class="tab-btn">
                    <p class="btn-create-topic create_topic text-center"><i class="fa fa-plus-circle"></i> Channel</p>
              </div>
          </div>
          <div class="tab-header tab-header-group clearfix hidden">
              <div class="tab-title">
                  <p class="tab-title-text">Groups</p>
                  <div class="groups-dropdown dropdown input-group">
                      <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
                      <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                          <li data-value="recent">Most recent</li>
                          <li data-value="post">Most posts</li>
                          <li data-value="view">Most viewed</li>
                      </ul>
                  </div>
              </div>
              <div class="tab-btn">
                  <p class="btn-create-topic" id="create_group"><i class="fa fa-plus-circle"></i>Create Group</p>
              </div>
          </div>
      </div>
      <div class="modal-body containt">
          <div id="tab_feed" class="tab">
              <p class="no-data">There is no data available yet</p>
          </div>
          <div id="tab_topic" class="tab">
              <div id="item_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">This community has no topics. Be the first to create a topic.</p>
              </div>
              <div id="item_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">This community has no topics. Be the first to create a topic.</p>
              </div>
              <div id="item_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">This community has no topics. Be the first to create a topic.</p>
              </div>
          </div>
          <div id="tab_groups" class="tab">

              <div class="topic_group_top">
                  <div class="topic_group_name">
                      <span>Football experts</span>
                      <button>Total Users</button>
                  </div>
                  <div class="topic_group_create">
                      <button id="btn-create-topic" class="text-center"><i class="fa fa-plus-circle"></i> Channel</button>
                      <button id="btn-create-post" class="text-center"><i class="fa fa-plus-circle"></i> Line</button>
                  </div>
                  <div class="filter">
                      <div class="dropdown input-group">
                          <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
                          <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                              <li data-value="recent">Most recent</li>
                              <li data-value="post">Most posts</li>
                              <li data-value="view">Most viewed</li>
                          </ul>
                      </div>
                  </div>
              </div>

              <div id="item_group_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">

              </div>
              <div id="item_group_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">

              </div>
              <div id="item_group_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">

              </div>

              <div id="item_topic_group_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
              <div id="item_topic_group_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
              <div id="item_topic_group_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>

              <div class="filter_page" id="group_topic_post_filter_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
              <div class="filter_page" id="group_topic_post_filter_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
              <div class="filter_page" id="group_topic_post_filter_brilliant" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>

              <div class="filter_page" id="item_total_users">
                  <p class="no-data">No users available yet</p>
              </div>
              
          </div>
      </div>
    </div>
  </div>
</div>
  <script id="city_name" type="text/x-underscore-template">
    <span class="title"><%= city %></span>
  </script>
  <script id="favorite_btn_template" type="text/x-underscore-template">
    <a href="javascript:" class="btn-favorite" data-object-type="<%= 'city' %>"
       data-object-id="<%= city_id %>">
        <span class="favorite-status">
            <% if(is_favorite == true){%>
                Followed
            <% }else{ %>
                Follow
            <% } %>
        </span>
    </a>
  </script>
  <script id="topic_list" type="text/x-underscore-template" >
      <% _.each(topices,function(topic){ %>
          <div class="item clearfix" data-item="<%= topic.id %>">
            <div class="topic_post">
                <div class="name_topic">
                    <p><%= topic.title %></p>
                </div>
            </div>
            <div class="topic-actions text-right">
                <span class="topic-item">
                    <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View count" data-container="body"></i><%= topic.view_count%>
                </span>
                <span class="topic-item">
                    <i class="fa fa fa-file-text-o" data-toggle="tooltip" data-placement="top" title="Post count" data-container="body"></i><%= topic.post_count%>
                </span>
                <span class="topic-item">
                    <i class="fa fa-clock-o" data-toggle="tooltip" data-placement="top" title="Created" data-container="body"></i><%= topic.created_at%>
                </span>
                <!--<% if (topic.owner) { %>
                    <span class="">
                        <span class="edit-topic"><i data-id="<%= topic.id %>" class="fa fa-edit"  data-toggle="tooltip" data-placement="top" title="Edit&nbsp;<%= topic.title %>" data-container="body"></i></span>
                        <span><i data-id="<%= topic.id %>" class="fa fa-trash-o"  data-toggle="tooltip" data-placement="top" title="Delete" data-container="body"></i></span>
                    </span>
                <% } %>-->
            </div>
        </div>
    <% }); %>  
    </script>

<script id="feed_list" type="text/x-underscore-template" >
  <% if(feed.top_post.length > 0) { %>
  <div class="top-post">
    <div class="top-header">
      <p class="lp-title">Top Posts</p>
      <p class="lp-description">Check out some of the discussions on some of your favorite subjects</p>
    </div>
    <div class="top-post-content">
      <%
        var len_post = feed.top_post.length;
        _.each(feed.top_post,function(e,i){
          if(i == len_post - 1){%>
              <div class="post-row last-row" data-value="<%= e.id %>" data-user="<%= e.user_id %>">
          <% }else{ %>
              <div class="post-row" data-value="<%= e.id %>" data-user="<%= e.user_id %>">
          <% } %>
              <div class="avatar"><div class="image"><img src="<%= e.photo %>"></div></div>

              <div class="post">
                <p class="post-title"><%= e.title %></p>
                <div class="post-content"><%= e.content%></div>
              </div>
              <div class="action">
                <div class="chat"><i class="fa fa-comments"></i>Chat</div>

                <span class="brilliant">
                  <% if (e.brilliant_count) { %>
                    <%= e.brilliant_count%>
                  <%}else{%>
                    <%= 0 %>
                  <%}%>
                </span>
              </div>
            </div>
      <% }); %>
    </div>
  </div>
  <% } %>
  <% if(feed.top_topic.length > 0) { %>
      <div class="top-topic">
        <div class="top-header">
          <p class="lp-title">Top Topics</p>
          <p class="lp-description">Browse these topics of conversations</p>
        </div>
        <div class="top-topic-content ">
          <%
            var len_topic = feed.top_post.length;
            _.each(feed.top_topic,function(e,i){
              if(i == len_topic - 1){ %>
                  <div class="topic-row last-row" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-city-name="<%= e.city_name %>">
              <% }else{ %>
                  <div class="topic-row" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-city-name="<%= e.city_name %>">
              <% } %>
                    <p class="topic-title"><%= e.name %></p>
                    <div class="post-counter">
                      <%= e.post_count %>
                      <span class="arrow"><i class="fa fa-angle-right"></i></span>
                      <i class="fa fa-file-text"></i>
                    </div>
                  </div>
          <%
            });
          %>

        </div>
      </div>
 <% } %>
 <% if(feed.feed.length > 0) { %>
  <div class="top-feed">
    <div class="top-header">
      <p class="lp-title">Feed</p>
    </div>
    <div class="top-feed-content"></div>
  </div>
  <% } %>

  <% if(feed.weather_feed.length > 0) { %>
  <div class="weather-feed-content">
      <div class="top-header">
          <p class="lp-title">Weather feed</p>
          <p class="lp-description">Check weather details this location</p>
      </div>
      <% _.each(feed.weather_feed, function(weatherData,i){ %>
          <% if(!(typeof weatherData == undefined || weatherData == null || $.isEmptyObject(weatherData))) { %>
              <div class="weather-data clearfix">
                  <section class="left-section pull-left">
                      <div class="group-title">Weather Data</div>
                      <% if(!$.isEmptyObject(weatherData.main)) {%>
                          <ul class="list-unstyled">
                              <li>
                                  <span class="weather-key">Temp</span>:
                                  <span class="weather-value">
                                      <b>
                                          <%= (parseFloat(weatherData.main.temp) - parseFloat(273)).toPrecision(2) %> &#8451;
                                      </b>
                                  </span>
                              </li>
                              <li>
                                  <span class="weather-key">Humidity</span>: <span class="weather-value"><b><%= weatherData.main.humidity %> %</b></span>
                              </li>
                              <li>
                                  <span class="weather-key">Pressure</span>: <span class="weather-value"><b><%= weatherData.main.pressure %> hpa</b></span>
                              </li>
                              <li>
                                  <span class="weather-key">Temp Min</span>:
                                  <span class="weather-value">
                                      <b>
                                          <%= (parseFloat(weatherData.main.temp_min) - parseFloat(273)).toPrecision(2)%> &#8451;
                                      </b>
                                  </span>
                              </li>
                              <li>
                                  <span class="weather-key">Temp Max</span>:
                                  <span class="weather-value">
                                  <b>
                                      <%= (parseFloat(weatherData.main.temp_max) - parseFloat(273)).toPrecision(2)%> &#8451;
                                  </b>
                                  </span>
                              </li>
                          </ul>
                      <% } %>
                  </section>
                  <% if(!$.isEmptyObject(weatherData.coord) || weatherData.weather.length > 0 ) {%>
                  <section class="right-section pull-right">
                      <div class="group-title">Weather Description</div>
                      <ul class="list-unstyled">
                          <li>
                              <span class="weather-key">Description</span>: <span class="weather-value"><b><%= weatherData.weather[0].description %></b></span>
                          </li>
                          <li>
                              <span class="weather-key">Latitude</span>: <span class="weather-value"><b><%= weatherData.coord.lat %></b></span>
                          </li>
                          <li>
                              <span class="weather-key">Longitude</span>: <span class="weather-value"><b><%= weatherData.coord.lon %></b></span>
                          </li>
                      </ul>
                  </section>
                  <% } %>
              </div>
          <% } else {%>
            No weather data available.
          <% } %>
      <% }); %>
  </div>
  <% } %>
  <% if(!$.isEmptyObject(feed.job_feed) && feed.job_feed.results.length > 0) { %>
      <div class="job-feed-content">
          <section class="top-header">
              <p class="lp-title">Job feed</p>
              <p class="lp-description">Check weather details this location</p>
          </section>
          <section class="job-details">
              <% _.each(feed.job_feed.results,function(item, i){ %>
              <div class="job-feed-row">
                  <div>
                      <span class="job-title"><%= item.jobtitle %></span>
                      <span><a class="job-url" href="<%= item.url %>" target="_blank">View</a></span>
                  </div>
                  <div><span class="company"><%= item.company %></span> - <span class="location"><%= item.formattedLocation %></span></div>
                  <div><span class="snippet"><%= item.snippet %></span></div>
              </div>
              <% }); %>
          </section>
      </div>
  <% } %>
</script>
<script id="top_feed" type="text/x-underscore-template">
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
          <span class='topic-create-by'>Topic created by: <%= e.created_by %></span>
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

