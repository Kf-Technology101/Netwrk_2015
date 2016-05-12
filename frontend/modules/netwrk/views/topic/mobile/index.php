<?php use yii\helpers\Url;
    // var_dump($data);die;
?>
<div id="show-topic" data-city="<?= $city_id ?>" <?php if ($data->status == 0){ echo 'data-zipcode="'.$data->zipcode.'" data-lat="'.$data->lat.'" data-lng="'.$data->lng.'" data-name="'.$data->city_name.'"'; } ?>>
    <div class="header">
        <div class="back_page <?php if($data->title) print 'back_help';?>">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="Favorite-btn-wrap">
            <a href="javascript:" class="btn-favorite" data-object-type="<?php echo 'city'; ?>"
               data-object-id="<?php echo $city_id; ?>">
                <span class="favorite-status">
                    <?php if($is_favorite == true): ?>
                        Following
                    <?php else: ?>
                        Follow
                    <?php endif; ?>
                </span>
            </a>
        </div>

        <div class="title_page">
            <span class="title"><?php if($data->title) print $data->title; else print $data->zipcode?></span>
        </div>
        <!--<div class="create_topic">
            <span><i class="fa fa-plus-circle"></i> Create Topic</span>
        </div>
        <div class="create_group" id="create_group">
            <span><i class="fa fa-plus-circle"></i> Create Group</span>
        </div>-->
    </div>
    <div class="sidebar">
        <!--<span class="filter"><i class="fa fa-filter"></i></span>-->
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
            <p class="btn-create-topic create_topic"><i class="fa fa-plus-circle"></i>Create Topic</p>
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
    <!--<div class="filter_sort">
        <div class="dropdown input-group">
            <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
            <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li data-value="recent">Most recent</li>
                <li data-value="post">Most posts</li>
                <li data-value="view">Most viewed</li>
            </ul>
        </div>
    </div>-->
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
        <div id="tab_groups" class="tab">
            <div class="topic_group_top">
                <div class="topic_group_name">
                    <span>Football experts</span>
                    <button>Total Users</button>
                </div>
                <div class="topic_group_create">
                    <button id="btn-create-topic">Create Topic</button>
                    <button id="btn-create-post">Create Post</button>
                </div>
                <div class="filter">
                    <div class="group-topics-dropdown dropdown input-group">
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
                <p class="no-data">There is no data available yet</p>
            </div>
            <div id="item_group_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
            <div id="item_group_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
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
        <script id="topic_list" type="text/x-underscore-template" >
            <% _.each(topices,function(topic){ %>
            <div class="item clearfix" data-item="<%= topic.id %>">
                <div class="topic_post">
                    <div class="name_topic" data-item="<%= topic.id %>">
                        <p><%= topic.title %></p>
                    </div>
                </div>
                <div class="topic-actions text-right">
                    <% if( topic.lat != null && topic.lng != null) {%>
                <span class="topic-item">
                    <i class="fa fa-map-marker topic-marker" data-toggle="tooltip" data-placement="top" title="View topic on map" data-container="body" data-lat="<%= topic.lat %>" data-lng="<%= topic.lng %>" data-city_id="<%= topic.city_id %>"></i>
                </span>
                    <% } %>
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
        <script id="group_list" type="text/x-underscore-template">
            <% if(groups.length > 0) {%>
            <% _.each(groups,function(group){ %>
            <div class="item clearfix" data-item="<%= group.id %>">
                <div class="group_loc_post">
            <span class="name_group">
                <p><%= group.name %></p>
            </span>
                </div>


                <div class="group-date-details group-item">
                    <span><%= group.created_at%></span>
                </div>
                <div class="group-actions text-right">
            <span class="group-item">
                <span class="most_post">
                    <span><% if (group.permission == 1) { %><i class="fa fa-globe" data-toggle="tooltip" data-placement="top" title="Public" data-container="body"></i><% } else if (group.permission == 2) { %><i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Private" data-container="body"></i><% } %></span>
                </span>
            </span>
            <span class="group-item">
                <span class="most_post">
                    <span><i class="fa fa-users" data-toggle="tooltip" data-placement="top" title="Users" data-container="body"></i><%= group.users%></span>
                </span>
            </span>
                    <% if (group.owner) { %>
            <span class="group-item">
                <span class="most_post">
                    <span class="edit-group-p"><i data-id="<%= group.id %>" class="edit-group fa fa-edit"  data-toggle="tooltip" data-placement="top" title="Edit&nbsp;<%= group.name %>" data-container="body"></i></span>
                    <span><i data-id="<%= group.id %>" class="delete-group fa fa-trash-o"  data-toggle="tooltip" data-placement="top" title="Delete" data-container="body"></i></span>
                </span>
            </span>
                    <% } %>
            <span class="group-item">
                <span class="most_post">
                    <span><i class="fa fa-angle-right"></i></span>
                </span>
            </span>
                </div>
            </div>
            <% }); %>
            <% } else {%>
            <div class="no-data-alert"><p class="">This community has no groups. Be the first to create a group.</p></div>
            <% } %>

        </script>
        <script id="topic_group_list" type="text/x-underscore-template">
            <% _.each(topices,function(topic){ %>
            <div class="item" data-item="<%= topic.id %>">
                <div class="topic_post">
                    <div class="name_topic">
                        <p><%= topic.title %></p>
                    </div>
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
        <script id="total_users" type="text/x-underscore-template" >
            <div class="users-joined">
                <div class="item-title">Joined people</div>
                <p class="no-data">No users</p>
                <% _.each(joined,function(user){ %>
                <div class="item" data-item="<%= user.id %>">
                    <div class="name">
                        <p><%= user.name %></p>
                    </div>
                    <div class="email">
                        <p><%= user.email%></p>
                    </div>
                </div>
                <% }); %>
            </div>
            <div class="users-invited">
                <div class="item-title">Invitation pending</div>
                <p class="no-data">No users</p>
                <% _.each(invited,function(user){ %>
                <div class="item" data-item="<%= user.id %>">
                    <div class="name">
                        <p><%= user.name %></p>
                    </div>
                    <div class="email">
                        <p><%= user.email%></p>
                    </div>
                </div>
                <% }); %>
            </div>
        </script>
    </div>
</div>
