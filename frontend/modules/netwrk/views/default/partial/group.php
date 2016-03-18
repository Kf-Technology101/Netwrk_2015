<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_group'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="header">
                    <div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="title_page">
                    </div>
                    <div class="create_topic" id="create_topic_loc">
                        <span><i class="fa fa-plus-circle"></i> Create Topic</span>
                    </div>
                    <div class="create_topic" id="create_group_loc">
                        <span><i class="fa fa-plus-circle"></i>Create Group</span>
                    </div>
                </div>

            </div>
            <div class="modal-body containt">
                <div id="tab_groups_loc" class="tab">

                    <div class="topic_group_loc_top">
                        <div class="topic_group_loc_name">
                            <span>Football experts</span>
                            <button>Total Users</button>
                        </div>
                        <div class="topic_group_loc_create">
                            <button id="btn-create-topic-loc">Create Topic</button>
                            <button id="btn-create-post-loc">Create Post</button>
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

                    <div id="item_group_loc_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>
                    <div id="item_group_loc_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>
                    <div id="item_group_loc_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>

                    <div id="item_topic_group_loc_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>
                    <div id="item_topic_group_loc_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>
                    <div id="item_topic_group_loc_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>

                    <div class="filter_page" id="group_loc_topic_post_filter_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>
                    <div class="filter_page" id="group_loc_topic_post_filter_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>
                    <div class="filter_page" id="group_loc_topic_post_filter_brilliant" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                        <p class="no-data">There is no data available yet</p>
                    </div>

                    <div class="filter_page" id="item_total_users_loc">
                        <p class="no-data">No users available yet</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
  <script id="city_name" type="text/x-underscore-template">
    <span class="title"><a href="javascript:void(0)"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a><%= city %></span>
  </script>
<script id="group_list" type="text/x-underscore-template">
    <% _.each(groups,function(group){ %>
    <div class="item" data-item="<%= group.id %>">
        <div class="group_loc_post">
            <div class="name_group">
                <p><%= group.name %></p>
            </div>
        </div>
        <div class="num_count_duration">
            <div class="most_post">
                <p><i class="fa fa-clock-o"></i><%= group.created_at%></p>
            </div>
        </div>
        <div class="num_count">
            <div class="most_post">
                <p><% if (group.permission == 1) { %><img src="/img/icon/glob.png"><% } else if (group.permission == 2) { %><img src="/img/icon/lock.png"><% } %></p>
            </div>
        </div>
        <div class="num_count_duration">
            <div class="most_post">
                <p><img src="/img/icon/users.png"><%= group.users%></p>
            </div>
        </div>
        <% if (group.owner) { %>
        <div class="num_count_duration">
            <div class="most_post">
                <p class="edit-group-p"><img data-id="<%= group.id %>" class="edit-group" src="/img/icon/edit-group.png"></p>
                <p><img data-id="<%= group.id %>" class="delete-group" src="/img/icon/delete-group.png"></p>
            </div>
        </div>
        <% } %>
    </div>
    <% }); %>
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
<script id="post_topic_group_list" type="text/x-underscore-template" >
    <% _.each(posts,function(post){ %>
    <div class="item_post" data-item="<%= post.id %>">
        <div class="users_avatar">
            <div class="image"><img src="<%= post.avatar %>"></div>
            <div class="icon_brillant" data-item="<%= post.id %>">
                <% if (post.is_vote == 1){%>
                <div class="count"><%= post.num_brilliant %></div>
                <% }else{ %>
                <div class="count disable"><%= post.num_brilliant %></div>
                <% } %>

            </div>
        </div>
        <div class="information">
            <span class="post_name"><%= post.title %></span>
            <p class="post_massage"><%= post.content %></p>
            <span class="post_chat"><i class="fa fa-comments"></i>Chat</span>
        </div>
    </div>
    <% }); %>
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