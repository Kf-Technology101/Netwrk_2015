<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_topic'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="header">
            <div class="back_page">
              <span><i class="fa fa-arrow-circle-left"></i> Back </span>
            </div>
            <div class="title_page">
            </div>
            <div class="create_topic" id="create_topic">
              <span><i class="fa fa-plus-circle"></i> Create Topic</span>
            </div>
              <div class="create_topic" id="create_group">
                  <span><i class="fa fa-plus-circle"></i>Create Group</span>
              </div>
          </div>
          <div class="sidebar">
            <div class="title"></div>
            <div class="dropdown input-group">
                <div class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Most recent</div>
                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <li data-value="recent">Most recent</li>
                  <li data-value="post">Most posts</li>
                  <li data-value="view">Most viewed</li>
                </ul>
            </div>
            <table class="filter_sidebar">
                <tr>
                    <td class="feed">Feed</td>
                    <td class="topic active">Topics</td>
                    <td class="groups">Groups</td>
                </tr>
            </table> 
          </div>

      </div>
      <div class="modal-body containt">
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
              <div id="item_group_list_post" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
              <div id="item_group_list_view" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
              <div id="item_group_list_recent" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                  <p class="no-data">There is no data available yet</p>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
  <script id="city_name" type="text/x-underscore-template">
    <span class="title"><a href="javascript:void(0)"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a><%= city %></span>
  </script>
  <script id="topic_list" type="text/x-underscore-template" >
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

