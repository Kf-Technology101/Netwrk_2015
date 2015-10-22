<?php use yii\helpers\Url; ?>
<div id="show-topic" data-city="<?= $city_id ?>">
    <div class="header">
        <div class="back_page">
            <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>">
        </div>

        <div class="title_page">
            <span class="title"><a href="<?php echo Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk_icon_small_hdpi.png'); ?>"></a><?php print $city_name?></span>
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
        <div id="item_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
            <p class="no-data">There is no data available yet</p>
        </div>
        <div id="item_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
            <p class="no-data">There is no data available yet</p>
        </div>
        <div id="item_list_topic" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
            <p class="no-data">There is no data available yet</p>
        </div>
        <script id="topic_list" type="text/x-underscore-template" >
            <% _.each(topices,function(topic){ %>
                <div class="item" data-item="<%= topic.id %>"> 
                    <div class="name_topic">
                        <p><%= topic.title %></p>
                    </div>
                    <div class="time_ago">
                    <% if (topic.created_at == 'Just now') {%>
                        <span><%= topic.created_at%></span>
                    <%}else{ %> 
                        <img src="<%= topic.img%>"/>
                        <span><%= topic.created_at%></span>
                    <% } %>    
                    </div>
                    <div class="num_count">
                        <div class="most_post">
                            <p><%= topic.view_count%></p>
                        </div>
                        <% if (topic.view_count == 1) {%>
                            <p>View</p>
                        <%}else{ %> 
                            <p>Views</p>
                        <% } %>    
                    </div>     
                </div>
            <% }); %>            
        </script>
        
    </div>
</div>