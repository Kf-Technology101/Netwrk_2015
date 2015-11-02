<?php use yii\helpers\Url; ?>
<div id="list_post" data-topic="<?= $topic ?>" data-city="<?= $city ?>">
    <div class="header">
        <div class="back_page">
            <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>">
        </div>
        <div class="create_post">
            <span><i class="fa fa-plus"></i> create</span>
        </div>
        <div class="title_page">
            <span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>"></a>Create a Post</span>
        </div>
    </div>
    <div class="sidebar">
        <div class="dropdown">
            <select class="form-control">
                <option>Most post</option>
                <option>Most brilliant</option>
                <option>Most view</option>
            </select>    
        </div>
        <table class="filter_sidebar">
            <tr>
                <td class="feed active">Feed</td>
                <td class="post">Post</td>
            </tr>
        </table> 
    </div>
    <div class="container_post">
        <div id="tab_feed" class="tab">
            <p class="no-data">There is no data available yet</p>
        </div>
        <div id="tab_post" class="tab">
            <div id="filter_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
<!--                 <div class="item_post">
                    <div class="users_avatar">
                        <img src="/netwrk/frontend/web/img/icon/no_avatar.jpg">
                    </div>
                    <div class="information">
                        <p class="post_name">#Train in Indiana</p>
                        <p class="post_massage">Test train indianaTest train indianaTest train indianaTest1234567890...<a class='show_more' href="javascript:void(0)">show more</a></p>
                    </div>
                    <div class="icon_information">
                        <div class="icon_duration">
                            <img src="<?= Url::to('@web/img/icon/timehdpi.png') ?>">
                            <p>Now</p>
                        </div>
                        <div class="icon_view">
                            <div class="num_view">
                                <p>20</p>
                            </div>
                            <p>Views</p>
                        </div>
                        <div class="icon_brillant">
                          <div class="count">1</div>
                          <p>Brilliant</p>
                        </div>
                        <div class="btn_comment">
                            <div class="num_comment"><p>999k</p></div>
                            <p> Comments</p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- <div id="list_post_recent" data-img="<?#= Url::to('@web/img/icon/timehdpi.png'); ?>"> -->
            <!-- <p class="no-data">There is no data available yet</p> -->
        <!-- </div>  -->
    </div>
</div>
<script id="name_post_list" type="text/x-underscore-template" >
    <span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>"></a>Create a Post</span>
</script>
<script id="post_list" type="text/x-underscore-template" >
    <% _.each(posts,function(post){ %>
        <div class="item_post">
            <div class="users_avatar">
                <img src="<%= post.avatar %>">
            </div>
            <div class="information">
                <p class="post_name"><%= post.title %></p>
                <p class="post_massage"><%= post.title %></p>
            </div>
            <div class="icon_information">
                <div class="icon_duration">
                    <img src="<?= Url::to('@web/img/icon/timehdpi.png') ?>">
                    <p><%= post.update_at %></p>
                </div>
                <div class="icon_view">
                    <div class="num_view">
                        <p><%= post.num_view %></p>
                    </div>
                    <p>Views</p>
                </div>
                <div class="icon_brillant">
                  <div class="count"><%= post.num_brilliant %></div>
                  <p>Brilliant</p>
                </div>
                <div class="btn_comment">
                    <div class="num_comment"><p><%= post.num_comment %></p></div>
                    <p> Comments</p>
                </div>
            </div>
        </div>
    <% }); %>            
</script>