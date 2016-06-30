<?php use yii\helpers\Url; ?>

<section class="Profile profile-page Profile-view">
    <article class="header">
        <div class="back-page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title-page">
            <span class="title">Profile</span>
        </div>
        <div class="setting user-details-wrapper pull-right">
            <span class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i></span>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class=''><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
                <li class=''><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
                <li class=''><a href="javascript:" id="my_profile_info"><i class='fa fa-user'></i> My profile info</a></li>
                <li class=''><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>
            </ul>
        </div>
    </article>

    <section class="profile-container">
        <section class="profile-info clearfix">

        </section>
        <div class="profile-activity-wrapper">
            <section class="fav-communities-wrapper">
                <div class="activity-header pull-left">Followed</div>
                <div class="seperator-line pull-right">
                    <hr>
                </div>
                <div class="clearfix form-group"></div>
            </section>

            <article class="fav-communities_content-wrapper">

            </article>

            <section class="recent-communities-wrapper">
                <div class="activity-header pull-left">Recent</div>
                <div class="seperator-line pull-right">
                    <hr>
                </div>
                <div class="clearfix form-group"></div>
            </section>

            <article class="recent-communities_content-wrapper">

            </article>

            <section class="recent_activities_wrapper">
                <div class="activity-header pull-left">Recent Activities</div>
                <div class="seperator-line pull-right">
                    <hr>
                </div>
                <div class="clearfix form-group"></div>

                <article class="row">
                    <div class="col-xs-12 text-center">
                        <div role="group" class="btn-group btn-group-default navigation-btn-group">
                            <button class="btn btn-default group" type="button" id="">
                                <span>Groups</span>
                            </button>
                            <button class="btn btn-default topic" type="button" id="">
                                <span>Topics</span>
                            </button>
                            <button class="btn btn-default post" type="button" id="">
                                <span>Posts</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="group-count">
                            My Groups: 90
                        </div>
                    </div>
                </article>
                <article id="recent_activity_container" class="">

                </article>
                <article class="clearfix">
                    <a href="javascript:" class="load-more">
                        <p class="text-right" style="padding-bottom: 20px;">
                            Load More...
                        </p>
                    </a>
                </article>
            </section>
        </div>
    </section>
</section>
<div class="modal" id='modal_change_profile_picture'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body container_chagne_avatar">
                <div class="image-preview">
                    <p>IMAGE PREVIEW</p>
                    <div class="preview_img"></div>
                    <div class="preview_img_ie"></div>
                </div>
                <div class="btn-control-modal">
                    <div class="btn-img cancel">
                        <p>Cancel</p>
                    </div>
                    <div class="btn-img save disable">
                        <i class="fa fa-check"></i>
                        <span>Save</span>
                    </div>
                    <div class="btn-img browse">
                        <?php
                        $form = \yii\widgets\ActiveForm::begin([
                            'action' => Url::to(['/netwrk/setting/upload-image']),
                            'options' => [
                                'id' => 'upload_image',
                                'enctype' => 'multipart/form-data',
                            ]
                        ]);
                        ?>
                        <!-- <form id="upload_image" method="post" action="<?= Url::to(['/netwrk/setting/upload-image']) ?>" enctype="multipart/form-data"> -->
                        <input type="file" id="input_image" name='image' accept="image/jpg,image/png,image/jpeg,image/gif">
                        <!-- </form> -->
                        <?php \yii\widgets\ActiveForm::end(); ?>
                        <p>Browse</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="profile_info" type="text/x-underscore-template">
    <div class="cover-photo">
        <img class="img-responsive" src="<?= Url::to('@web/img/background/cover-bg.png'); ?>">
        <div class="change-cover"><i class="fa fa-camera"></i> Edit cover image</div>
    </div>
    <div class="profile-picture pull-left">
        <div class="img-user text-center"><img src="<%= data.image %>"></div>
        <div class="change-profile">
            <i class="fa fa-camera"></i>
        </div>
    </div>
    <div class="user-details-wrapper clearfix">
        <div class="user-details pull-left">
            <div class="user-name"><%= data.username %>, <%= data.year_old %></div>
            <div class="user-location"><%= data.city %>, <%= data.state %>, <%= data.country %></div>
        </div>
        <div class="brillant pull-right">
            <div class="count">
                <span>0</span>
            </div>
        </div>
    </div>
</script>
<script id="profile_group_info" type="text/x-underscore-template">
    <div class="group-details activity-details">
        <table class="table no-border">
            <% if(!_.isEmpty(groups)) {%>
            <% _.each(groups,function(items, key){ %>
            <div class="group-item" id="profileRecentTopic">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="strike">
                            <span><%= key %></span>
                        </div>
                    </div>
                </div>
                <% _.each(items,function(item, index){ %>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="item">
                            <div class="row">
                                <div class="col-xs-6">
                                    <a href="javascript:" class="title group-trigger"
                                       data-id="<%= item.id %>"
                                       data-city-id="<%= item.city_id %>"
                                       data-city-zip="<%= item.city_zip%>">
                                        <b><%= item.name %></b>
                                    </a>
                                </div>
                                <div class="col-xs-6">
                                    <div class="topic-actions text-right">
                                        <a href="javascript:" class=""><i class="fa fa-edit"></i><span>Edit</span></a>
                                        <a href="javascript:" class="delete-trigger" data-section="profile" data-object="group" data-id="<%= item.id %>"><i class="fa fa-trash-o"></i><span>Delete</span></a>
                                            <span class="date-details">
                                               <%= item.formatted_created_date %>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <% }); %>
            </div>
            <% }); %>
            <% } else {%>
            <div class="group-item">
                <div class="alert alert-info">You haven't created any group yet. Please check out any community and create a group.</div>
            </div>
            <% } %>
        </table>
    </div>
</script>

<script id="profile_topic_info" type="text/x-underscore-template">
    <div class="topic-details activity-details">
        <% if(!_.isEmpty(topics)) {%>
        <% _.each(topics,function(items, key){ %>
        <div class="group-item" id="profileRecentTopic">
            <div class="row">
                <div class="col-xs-12">
                    <div class="strike">
                        <span><%= key %></span>
                    </div>
                </div>
            </div>
            <% _.each(items,function(item, index){ %>
            <div class="row">
                <div class="col-xs-12">
                    <div class="item">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="javascript:" class="title topic-trigger" data-value="<%= item.id %>" data-city="<%= item.city_id %>" data-city-name="<%= item.city_name %>"><%= item.title %></a>
                            </div>
                            <div class="col-xs-6">
                                <div class="topic-actions text-right">
                                    <a href="javascript:" class="edit-topic" data-id="<%= item.id %>" data-city="<%= item.city_id %>" data-city_name="<%= item.city_name %>"><i class="fa fa-edit"></i><span>Edit</span></a>
                                    <a href="javascript:" class="delete-trigger" data-section="profile" data-object="topic" data-id="<%= item.id %>"><i class="fa fa-trash-o"></i><span>Delete</span></a>
                                            <span class="date-details">
                                               <%= item.formatted_created_date %>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <% }); %>
        </div>
        <% }); %>
        <% } else {%>
        <div class="group-item">
            <div class="alert alert-info">You haven't created any topic yet. Please check out any community and create a topic.</div>
        </div>
        <% } %>
    </div>
</script>

<script id="profile_post_info" type="text/x-underscore-template">
    <div class="post-details activity-details" id="recentActivityPosts">
        <% if(!_.isEmpty(posts)) {%>
        <% _.each(posts,function(items, key){ %>
        <div class="group-item">
            <div class="row">
                <div class="col-xs-12">
                    <div class="strike">
                        <span><%= key %></span>
                    </div>
                </div>
            </div>
            <% _.each(items,function(item, index){ %>
            <div class="row">
                <div class="col-xs-12">
                    <div class="item">
                        <div class="row" data-value="<%= item.id %>" data-user="<%= item.user_id %>">
                            <div class="col-xs-6 post">
                                <p class="post-title"><%= item.title %></p>
                                <div class="post-content"><%= item.content %></div>
                            </div>
                            <div class="col-xs-6 text-right">
                                <div class="date-details">
                                    <% print(item.formatted_created_date) %>
                                </div>
                                <div class="post-actions">
                                    <a href="javascript:" class="post-edit" data-id="<%= item.id %>" data-topic_id="<%= item.topic_id %>" data-city_id="<%= item.city_id %>"><i class="fa fa-edit"></i><span>Edit</span></a>
                                    <a href="javascript:" class="delete-trigger" data-section="profile" data-object="post" data-id="<%= item.id %>"><i class="fa fa-trash-o"></i><span>Delete</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <% }); %>
        </div>
        <% }); %>
        <% } else {%>
        <div class="group-item">
            <div class="alert alert-info">You haven't created any post yet. Please check out any community and create a post.</div>
        </div>
        <% } %>
    </div>
</script>
<script id="profile_fav-communities_template" type="text/x-underscore-template">
    <div class="communities_list clearfix">
        <div class="communities-list" id="favoriteCommunities">
            <% if(!_.isEmpty(items)) {%>
                <% _.each(items, function(item, key){ %>
                    <div class="community">
                        <span class="zip-code pull-left">
                            <a class="community-modal-trigger"
                               href="javascript:"
                               data-lat="<%= item.lat %>"
                               data-lng="<%= item.lng %>"
                               data-city-id="<%= item.city_id %>">
                                <% if(item.city_office_type != null) { %>
                                    <%= item.city_office %>
                                <% } else { %>
                                    <%= item.city_name %>
                                <% } %>
                            </a>
                        </span>
                        <span class="community-action pull-right un-favorite-trigger"
                              data-object-type="<%= 'city' %>"
                              data-object-id="<%= item.city_id %>"><i class="fa fa-trash-o"></i>
                        </span>
                    </div>
                <% }); %>
            <% } else {%>
                <div class="alert alert-info">Currently there is no favorite communities</div>
            <% } %>
        </div>
    </div>
</script>
<script id="profile_recent-communities_template" type="text/x-underscore-template">
    <div class="clearfix">
        <div class="communities-list" id="recentCommunities">
            <% if(!_.isEmpty(items)) {%>
                <% _.each(items, function(item, key){ %>
                    <div class="community">
                        <span class="zip-code pull-left">
                            <a class="community-modal-trigger"
                               href="javascript:"
                               data-city-id="<%= item.city_id %>">
                                <% if(item.city_office_type != null) { %>
                                    <%= item.city_office %>
                                <% } else { %>
                                    <%= item.city_name %>
                                <% } %>
                            </a>
                        </span>
                        <span class="community-action pull-right remove-recent-trigger"
                              data-log_id="<%= item.log_id %>"
                              data-type="<%= 'city' %>"
                              data-city_id="<%= item.city_id %>"><i class="fa fa-trash-o"></i>
                        </span>
                    </div>
                <% }); %>
            <% } else {%>
                <div class="alert alert-info">Currently there is no recent communities</div>
            <% } %>
        </div>
    </div>
</script>
<?= $this->render('../../default/partial/confirm');?>
