<?php use yii\helpers\Url; ?>
<div id="show-topic" data-city="<?= $city ?>">
    <div class="header">
        <div class="back_page"><i class="fa fa-arrow-left"></i></div>
        <div class="title_page">
            <img src="<?= Url::to('@web/img/icon/netwrk_btn.png'); ?>">
            <span class="title">Indianapolis</span>
        </div>
        <div class="create_topic">
            <span>Create a topic +</span>
        </div>
    </div>
    <div class="sidebar">
       <span class="title">Topics</span>
       <table class="filter_sidebar">
            <tr>
                <td class="active post">Most posts</td>
                <td class="view">Most viewed</td>
                <td class="topic">My Topics</td>
            </tr>
       </table> 
    </div>
    <div class="container">
        <div id="item_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>"></div>
        <div id="item_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>"></div>
        <div id="item_list_topic" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>"></div>
        <script id="topic_list" type="text/x-underscore-template" >
            <% _.each(topices,function(topic){ %>
                <div class="item"> 
                    <div class="name_topic">
                        <span><%= topic.title %></span>
                    </div>
                    <div class="time_ago">
                        <img src="<%= topic.img%>"/>
                        <span><%= topic.created_at%></span>
                    </div>
                    <div class="num_count">
                        <div class="most_post">
                            <p><%= topic.view_count%></p>
                        </div>
                        <p>views</p>
                    </div>     
                </div>
            <% }); %>            
        </script>
        
    </div>
</div>