<?php
    use yii\helpers\Url;
    use yii\web\Cookie;
    $cookies = Yii::$app->request->cookies;
?>
<div class="post_slider left-slider" id="slider_list_post">
    <!-- <div id="btn_meet"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div> -->
    <!--<div class="modal-dialog">
        <div class="modal-content">-->
            <div class="slider-header">
                <div class="header">
                    <div class="title_page left-section">
                    </div>
                    <div class="middle-section">
                        <i class="fa fa-align-justify"></i>
                    </div>
                    <div class="right-section">
                        <div class="feedback-line"></div>
                        <span class="title">Welcome</span>
                    </div>
                    <!--<div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="title_page">
                    </div>
                    <div class="create_post">
                        <span><i class="fa fa-plus-circle"></i> Line</span>
                    </div>-->

                </div>
                <div class="sidebar">
                    <div class="title_page"></div>
                    <!--<div class="title"></div>
                    <div class="dropdown input-group">
                        <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
                        <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <li data-value="post">Most recent</li>
                            <li data-value="brilliant">Most brilliant</li>
                            <li data-value="view">Most viewed</li>
                        </ul>
                    </div>
                    <table class="filter_sidebar">
                        <tr>
                            <td class="feed">Area Vision</td>
                            <td class="post active">Lines</td>
                        </tr>
                    </table>-->
                </div>
            </div>
           <div class="slider-body">
               <div class="post-feedback">
                   <?= $this->render('@frontend/modules/netwrk/views/feedback/view') ?>
               </div>
               <div class="container_post">
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
               </div>
            </div>
        <!--</div>
    </div>-->
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
<script id="post_city_name" type="text/x-underscore-template">
    <span class="title">
        <% if(name.office_type == 'university') { %>
            <i class="fa fa-lg fa-university"></i>
            Idea area
        <% } else if(name.office_type == 'government') { %>
            <i class="fa fa-lg fa-institution"></i>
            Gov - Problem solving area
        <% } else { %>
            <i class="fa fa-lg fa-home"></i>
            Area HQ
        <% } %>
    </span>
</script>
<script id="post_general_post" type="text/x-underscore-template">
    <div class="feedback-line"></div>
    <span class="title" data-value="<%= post_id %>">
          <span class="post-trigger">
              <span class="post-title"><%= post_title %></span>
              <span class="post-content hidden"><%= topic_title %></span>
          </span>
      </span>
</script>
<script id="name_post_list" type="text/x-underscore-template" >
    <span class="title"><%= name.zipcode %> > <%= name.title %></span>
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
                                <% if(post.meet == 0 && post.post_user_id != 1) { %>
                                <span class="meet-img meet-trigger meet-<%= post.post_user_id%>" data-user-id="<%= post.post_user_id%>">Connect</span>
                                <% } %>
                            </div>
                            <!--<div class="icon_brillant" data-item="<%= post.id %>">
                                <div class="count <%= Post.getBrilliantCode(post.num_brilliant) %>"><%= post.num_brilliant %></div>
                            </div>-->
                        </div>
                        <div class="information">
                            <span class="post_name"><%= post.title %></span>
                            <!--<p class="post_massage"><%= post.content %></p>-->
                            <p class="post_topic hide"><%= post.topic_name %></p>
                            <!--<span class="post_chat"><i class="fa fa-comments"></i>Chat</span>-->
                        </div>

                        <div class="feedback-option">
                            <% if(isGuest){%>
                                <div class="feedback-trigger login-trigger <% if(i == 0){%><%= popoverClassPostFeedback %><%}%>"
                                     data-modal="Post"
                                <% if(i == 0){%>
                                    data-template='<div class="popover info-popover post-feedback-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_post_feedback" data-wrapper="popover-post-feedback">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
                                    data-placement="top"
                                    data-content="<%= postFeedbackPopover %>"
                                <% } %>>F</div>
                            <% } else { %>
                                <div class="feedback-trigger <% if(i == 0){%><%= popoverClassPostFeedback %><%}%>"
                                     data-parent="#slider_list_post"
                                     data-object="post"
                                     data-id="<%= post.id%>"
                                <% if(i == 0){%>
                                    data-template='<div class="popover info-popover post-feedback-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_post_feedback" data-wrapper="popover-post-feedback">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
                                    data-placement="top"
                                    data-content="<%= postFeedbackPopover %>"
                                <% } %>>F</div>
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
<script id="stream_list" type="text/x-underscore-template">
    <% _.each(streams,function(stream){ %>
        <div class="panel panel-default pull-left panel-stream">
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

