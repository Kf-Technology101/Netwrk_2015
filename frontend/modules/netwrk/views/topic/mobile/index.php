<?php use yii\helpers\Url;
    // var_dump($data);die;
?>
<div id="show-topic" data-city="<?= $city_id ?>" <?php if ($data->status == 0){ echo 'data-zipcode="'.$data->zipcode.'" data-lat="'.$data->lat.'" data-lng="'.$data->lng.'" data-name="'.$data->city_name.'"'; } ?>>
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>

        <div class="title_page">
            <span class="title"><?php print $data->zipcode?></span>
        </div>
        <div class="create_topic">
            <span><i class="fa fa-plus-circle"></i> Create Topic</span>
        </div>
    </div>
    <div class="sidebar">
        <span class="filter"><i class="fa fa-filter"></i></span>
        <table class="filter_sidebar">
            <tr>
                <td class="feed active">Feed</td>
                <td class="topic">Topics</td>
            </tr>
        </table>
    </div>
    <div class="filter_sort">
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
    <div class="container">
        <div id="tab_feed" class="tab">
            <p class="no-data">There is no data available yet</p>
        </div>
        <div id="tab_topic" class="tab">
            <div id="item_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
            <div id="item_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
            <div id="item_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
        </div>
        <script id="topic_list" type="text/x-underscore-template" >
            <% _.each(topices,function(topic){ %>
                <div class="item" data-item="<%= topic.id %>">
                    <div class="name_topic">
                        <p><%= topic.title %></p>
                    </div>
                    <div class="arrow">
                        <p><i class="fa fa-angle-right"></i></p>
                    </div>
                    <div class="num_count_duration">
                        <div class="most_post">
                            <p><i class="fa fa-clock-o"></i><%= topic.created_at%></p>
                        </div>
                    </div>
                    <div class="num_count">
                        <div class="most_post">
                            <p><i class="fa fa-file-text"></i><%= topic.post_count%></p>
                        </div>
                    </div>
                    <div class="num_count">
                        <div class="most_post">
                            <p><i class="fa fa-eye"></i><%= topic.view_count%></p>
                        </div>
                    </div>
                </div>
            <% }); %>
        </script>
        <script id="feed_list" type="text/x-underscore-template" >
            <div class="top-post">
                <div class="top-header">
                  <p class="lp-title">Top Posts</p>
                  <p class="lp-description">Check out some of the discussions on some of your favorite subjects</p>
                </div>
                <div class="top-post-content">
                  <%
                    var len_post = feed.top_post.length;
                    _.each(feed.top_post,function(e,i){
                      console.log(e);
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

                            <span class="brilliant"><%= e.brilliant_count%></span>
                          </div>
                        </div>
                  <%
                    });
                  %>
                </div>
            </div>
            <div class="top-topic">
              <div class="top-header">
                  <p class="lp-title">Top Topics</p>
                  <p class="lp-description">Browse these topics of conversations</p>
              </div>
              <div class="top-topic-content ">
                <%
                  var len_topic = feed.top_post.length;
                  _.each(feed.top_topic,function(e,i){
                    console.log(e);
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
            <div class="top-feed">
              <div class="top-header">
                <p class="lp-title">Feed</p>
              </div>
              <div class="top-feed-content"></div>
            </div>
        </script>
        <script id="top_feed" type="text/x-underscore-template">
          <%
            _.each(feed.feed,function(e,i){ %>
              <% if ((e.is_post == 1)){ %>
                <div class="feed-row feed-post" data-value="<%= e.id %>" data-city="<%= e.city_id %>" data-topic='<%= e.topic_id %>'>
                <div class="feed-content">
                  <div class="avatar-poster"><div class="image"><img src="<%= e.photo %>"></div></div>
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
    </div>
</div>