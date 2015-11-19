<?php use yii\helpers\Url; ?>
<div id="list_post" data-topic="<?= $topic->id ?>" data-city="<?= $city ?>">
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a><?= $topic->title ?></span>
        </div>
        <div class="create_post">
            <span><i class="fa fa-plus-circle"></i> Create Post</span>
        </div>
    </div>
    <div class="sidebar">
<!--         <div class="dropdown">
            <select class="form-control">
                <option value="post">Most recent</option>
                <option value="brilliant">Most brilliant</option>
                <option value="view">Most view</option>
            </select>    
        </div> -->
        <span class="filter"><i class="fa fa-filter"></i></span>
        <table class="filter_sidebar">
            <tr>
                <td class="feed">Feed</td>
                <td class="post active">Posts</td>
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
<script id="name_post_list" type="text/x-underscore-template" >
    <span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>"></a><%= name %></span>
</script>
<script id="post_list" type="text/x-underscore-template" >
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
                <p class="post_name"><%= post.title %></p>
                <p class="post_massage"><%= post.content %></p>
                <span class="post_chat"><i class="fa fa-comments"></i>Chat</span>
            </div>
        </div>
    <% }); %>            
</script>