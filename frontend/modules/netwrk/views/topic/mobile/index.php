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
                <td class="feed">Feed</td>
                <td class="topic active">Topics</td>
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
        
    </div>
</div>