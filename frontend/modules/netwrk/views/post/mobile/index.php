<?php
    use yii\helpers\Url;
    use yii\web\Cookie;
    $cookies = Yii::$app->request->cookies;
?>
<div id="list_post" data-topic="<?= $topic->id ?>" <?php if (!empty($city)) { ?>data-city="<?= $city ?>"<?php } ?> <?php if (!empty($city_id)) { ?>data-city="<?= $city_id ?>"<?php } ?>>
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title"><?php if (!empty($city)) { ?><?= $topic->city->zip_code ?> > <?php } ?><?= $topic->title ?></span>
        </div>
        <div class="create_post">
            <span><i class="fa fa-plus-circle"></i> Line</span>
        </div>
    </div>
    <div class="sidebar">
        <span class="filter"><i class="fa fa-filter"></i></span>
        <table class="filter_sidebar">
            <tr>
                <td class="feed">Area Vision</td>
                <td class="post active">Line</td>
            </tr>
        </table>
    </div>
    <div class="filter_sort">
        <div class="dropdown input-group">
            <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
            <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li data-value="post">Most recent</li>
                <li data-value="brilliant">Most brilliant</li>
                <li data-value="view">Most viewed</li>
            </ul>
        </div>
    </div>
    <div class="container_post">
        <div class="post-feedback">
            <?= $this->render('@frontend/modules/netwrk/views/feedback/view') ?>
        </div>
        <div id="tab_feed" class="tab">
            <p class="no-data">There is no data available yet</p>
        </div>
        <div id="tab_post" class="tab">
            <div class="filter_page" id="filter_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
            <div class="filter_page" id="filter_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
            <div class="filter_page" id="filter_brilliant" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                <p class="no-data">There is no data available yet</p>
            </div>
        </div>

        <!-- <div id="list_post_recent" data-img="<?#= Url::to('@web/img/icon/timehdpi.png'); ?>"> -->
            <!-- <p class="no-data">There is no data available yet</p> -->
        <!-- </div>  -->
    </div>
</div>
<script type="text/javascript">
    <?php if (isset($cookies["nw_popover_post_feedback"])) {?>
        var popoverClassPostFeedback = '',
            postFeedbackPopover = '';
    <?php } else { ?>
        var popoverClassPostFeedback = 'popover-post-feedback',
            postFeedbackPopover = 'Give some feedback to use the best discussion system ever built!';
    <?php } ?>

    <?php if (isset($cookies["nw_popover_post_filter"])) {?>
        var popoverClassPostFilter = '',
            postFilterPopover = '';
    <?php } else { ?>
        var popoverClassPostFilter = 'popover-post-filter',
            postFilterPopover = "Cycle through the line's highlights";
    <?php } ?>
</script>
<script id="name_post_list" type="text/x-underscore-template" >
    <span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>"></a><%= name %></span>
</script>
<script id="post_list" type="text/x-underscore-template" >
    <% _.each(posts,function(post, i){ %>
        <div class="panel panel-default panel-post">
            <div class="panel-heading" role="tab" id="heading<%= post.id %>">
                <div class="panel-title panel-post-title">
                    <a href="#collapse<%= post.id %>" role="button"
                       data-toggle="collapse" aria-controls="collapse<%= post.id %>"
                       class="<% if(post.feedback_points < 0) { %>collapsed<%}%>">
                        <div class="post-minimized">
                            <span class="user-name">
                                <span class="name"><%= post.user_name %></span>
                                <span class="feedback-img pull-right"></span>
                            </span>
                            <span class="time"><%= post.created_at %></span>
                            <!--<span class="post-name"><%= post.title %></span>-->
                            <i class="pull-right plus-icon"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div id="collapse<%= post.id %>" class="panel-collapse collapse <% if(post.feedback_points >= 0) { %>in<%}%>" aria-labelledby="heading<%= post.id %>">
                <div class="panel-body item-post-panel-body" data-item="<%= post.id %>" data-user="<%= post.user %>" data-chat-type='1'>
                    <div class="item_post">
                        <div class="users_avatar" data-user-post="<%= post.post_user_id %>">
                            <div class="image">
                                <img src="<%= post.avatar %>">
                                <span class="feedback-img pull-right"></span>
                                <% if(post.meet == 0) { %>
                                    <span class="meet-img meet-trigger meet-<%= post.post_user_id%>" data-user-id="<%= post.post_user_id%>"></span>
                                <% } %>
                            </div>
                            <!--<div class="icon_brillant" data-item="<%= post.id %>">
                                <div class="count <%= Post.getBrilliantCode(post.num_brilliant) %>"><%= post.num_brilliant %></div>
                            </div>-->
                        </div>
                        <div class="information">
                            <span class="post_name"><%= post.title %></span>
                            <p class="post_massage"><%= post.content %></p>
                            <!--<span class="post_chat"><i class="fa fa-comments"></i>Chat</span>-->
                        </div>
                        <div class="clearfix stream-options">
                            <div class="pull-left line">
                                <img src="<?= Url::to('@web/img/icon/line-icon-nav.png'); ?>" />
                            </div>
                            <div class="pull-left glow-btn-wrapper jump chat-trigger">
                                <div class="btn-active">Jump in</div>
                                <div class="btn-inactive">Jump in</div>
                            </div>
                            <% if(isGuest){%>
                                <div class="pull-left respond feedback-trigger login-trigger <% if(i == 0){%><%= popoverClassPostFeedback %><%}%>"
                                    data-modal="Post"
                                    <% if(i == 0){%>
                                        data-template='<div class="popover info-popover post-feedback-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_post_feedback" data-wrapper="popover-post-feedback">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
                                        data-placement="top"
                                        data-content="<%= postFeedbackPopover %>"
                                    <% } %>>Feedback</div>
                            <% } else { %>
                                <div class="pull-left respond feedback-trigger <% if(i == 0){%><%= popoverClassPostFeedback %><%}%>"
                                    data-parent="#list_post"
                                    data-object="post"
                                    data-id="<%= post.id%>"
                                    <% if(i == 0){%>
                                        data-template='<div class="popover info-popover post-feedback-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_post_feedback" data-wrapper="popover-post-feedback">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
                                        data-placement="top"
                                        data-content="<%= postFeedbackPopover %>"
                                    <% } %>>Feedback</div>
                            <% } %>
                        </div>
                    </div>

                    <div class="panel panel-default pull-left panel-post-stream">
                        <div class="panel-heading" role="tab" id="heading-post-stream-<%= post.id %>">
                            <div class="panel-title panel-post-stream-title">
                                <a href="#collapse-post-stream-<%= post.id %>" role="button"
                                   class="collapsed pull-left panel-trigger stream-trigger"
                                   data-toggle="collapse" aria-controls="collapse-post-stream-<%= post.id %>"
                                   data-post-id="<%= post.id%>" data-type="line" data-count="<%= post.stream_count%>">
                                    <i class="fa fa-chevron-up"></i>
                                    <i class="fa fa-chevron-down"></i>
                                </a>
                                <div class="post-stream-heading">
                                    <span class="stream-filters pull-left">
                                        <div class="pull-left line-stream stream-trigger <% if(i == 0){%><%= popoverClassPostFilter %><%}%>"
                                            data-post-id="<%= post.id%>"
                                            data-type="line"
                                            data-count="<%= post.stream_count%>"
                                            <% if(i == 0){%>
                                                data-template='<div class="popover info-popover post-filter-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_post_filter" data-wrapper="popover-post-filter">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
                                                data-placement="bottom"
                                                data-content="<%= postFilterPopover %>"
                                            <% } %>>
                                            <span class="count"><%= post.stream_count%></span>
                                            <span class="text-right">Highlights</span>
                                        </div>
                                        <div class="pull-left like-stream stream-trigger"
                                             data-post-id="<%= post.id%>" data-type="like" data-count="<%= post.like_feedback_count%>">
                                            <span class="count"><%= post.like_feedback_count%></span>
                                            <img src="<?= Url::to('@web/img/icon/feedback-option-1-hover.png'); ?>" />
                                        </div>
                                        <div class="pull-left fun-stream stream-trigger"
                                             data-post-id="<%= post.id%>" data-type="fun" data-count="<%= post.fun_feedback_count%>">
                                            <span class="count"><%= post.fun_feedback_count%></span>
                                            <img src="<?= Url::to('@web/img/icon/feedback-option-2-hover.png'); ?>" />
                                        </div>
                                        <div class="pull-left angle-stream stream-trigger"
                                             data-post-id="<%= post.id%>" data-type="angle" data-count="<%= post.angle_feedback_count%>">
                                            <span class="count"><%= post.angle_feedback_count%></span>
                                            <img src="<?= Url::to('@web/img/icon/feedback-option-3-hover.png'); ?>" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="collapse-post-stream-<%= post.id %>" class="panel-collapse collapse" aria-labelledby="heading-post-stream-<%= post.id %>">
                            <div class="panel-body panel-post-stream-body">
                                <div class="stream-wrapper" id="streamWrapper">
                                    <p class="no-data">There is no data available yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <% }); %>
</script>
<script id="feed_list" type="text/x-underscore-template" >
    <div class="top-post">
        <div class="top-header">
            <% if(feed.office_type == 'university') { %>
                <p class="lp-title">Welcome to your local Idea center</p>
                <p class="lp-description">Here are the top idea lines in the area</p>
            <% } else if(feed.office_type == 'government') { %>
                <p class="lp-title">Welcome to your local solution center</p>
                <p class="lp-description">Here are the top problem lines in the area</p>
            <% } else { %>
                <p class="lp-title">Welcome to your local Community Center</p>
                <p class="lp-description">Check out the most active lines in the area</p>
            <% } %>
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
            <% if(feed.office_type == 'university') { %>
                <p class="lp-title">Top Idea channels</p>
                <p class="lp-description">Here are the most active in the area</p>
            <% } else if(feed.office_type == 'government') { %>
                <p class="lp-title">Problem channels are the home of discourse on netwrk</p>
                <p class="lp-description">In problem channels, each line serves as a place to discuss an issue. Here are the most active in the area</p>
            <% } else { %>
                <p class="lp-title">Top Channels</p>
                <p class="lp-description">Here are the most active channels in the area</p>
            <% } %>
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
            <% if(feed.office_type == 'university') { %>
                <p class="lp-title">Idea Center News</p>
            <% } else if(feed.office_type == 'government') { %>
                <p class="lp-title">Solution Center News</p>
            <% } else { %>
                <p class="lp-title">Local News</p>
            <% } %>
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
<script id="stream_list" type="text/x-underscore-template">
    <% _.each(streams,function(stream){ %>
        <div class="panel panel-default panel-stream">
            <div class="panel-heading" role="tab" id="heading-stream-<%= stream.id %>">
                <div class="panel-title panel-stream-title">
                    <a href="#collapse-stream-<%= stream.id %>" role="button"
                       data-toggle="collapse" aria-controls="collapse-stream-<%= stream.id %>"
                       class="">
                        <div class="stream-minimized">
                            <span class="user-name">
                                <span class="name"><%= stream.user_name %></span>
                                <span class="feedback-img pull-right"></span>
                            </span>
                            <span class="time"><%= stream.created_at %></span>
                            <i class="pull-right plus-icon"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div id="collapse-stream-<%= stream.id %>" class="panel-collapse collapse in" aria-labelledby="heading-stream-<%= stream.id %>">
                <div class="panel-body panel-stream-body">
                    <div class="user-avatar">
                        <div class="image">
                            <img src="<%= stream.avatar %>">
                            <span class="feedback-img pull-right"></span>
                        </div>
                    </div>
                    <div class="information">
                        <p class="stream-massage"><%= stream.msg %></p>
                    </div>
                    <div class="bottom-actions">
                        <span class="jump-to chat-trigger" data-id="<%= stream.id %>">Jump to</span>
                        <span class="more chat-trigger">Show more</span>
                        <span class="respond-to chat-trigger" data-id="<%= stream.id %>">Feedback</span>
                    </div>
                </div>
            </div>
        </div>
    <% }); %>
</script>
