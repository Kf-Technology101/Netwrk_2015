<?php use yii\helpers\Url; ?>
<div class="modal modal-profile" id='modal_profile'>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <div class="header">
                <div class="back-page">
                    <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                </div>
                <div class="title-page">
                    <span class="title">Profile</span>
                </div>
            </div>
        </div>
        <div class="modal-body profile-container">
            <div class="profile-info">

            </div>

            <div class="profile-activity-wrapper">
                <section class="fav-communities-wrapper">
                    <div class="activity-header pull-left">Favorite Communities </div>
                    <div class="seperator-line pull-right">
                        <hr>
                    </div>
                    <div class="clearfix form-group"></div>
                </section>
                <article>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sit amet quam tortor. Donec sed neque vitae lacus hendrerit tempor. Phasellus arcu mi, varius vitae dignissim eget, pellentesque vel dui. Vivamus vitae sagittis mi. Duis ut risus luctus, sagittis odio at, tempor nisl. Vivamus viverra ornare viverra. Quisque lacus nulla, iaculis ut elementum at, gravida dapibus nunc. Suspendisse in interdum felis, sit amet pretium urna. Quisque tincidunt libero eget augue porta rhoncus. Etiam suscipit placerat metus. Mauris a neque in libero ultrices volutpat eget eget dui.

                        Pellentesque vulputate sodales urna et vulputate. Fusce lobortis nibh et ligula vehicula convallis. Donec dui tellus, posuere et iaculis in, imperdiet nec massa. Integer gravida dapibus blandit. Vestibulum non augue ut risus mollis efficitur eget in leo. Maecenas gravida, odio sed finibus venenatis, mauris arcu venenatis dolor, nec vehicula nulla felis nec urna. Pellentesque id dolor sagittis, cursus lorem ut, lobortis tortor. Maecenas aliquam massa dolor, nec pharetra nulla porta non. Suspendisse potenti. Mauris magna turpis, malesuada at venenatis vel, tincidunt ut arcu. Vestibulum et turpis mollis, laoreet mauris ac, faucibus est. Donec pulvinar, nisl quis pulvinar pretium, ipsum augue ullamcorper orci, nec luctus diam ante sed metus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.

                        Etiam at risus porttitor, condimentum dui sit amet, maximus leo. Aenean eu felis nunc. Nulla et pulvinar odio. Nam viverra neque et est dictum, nec blandit ante efficitur. Sed aliquam felis commodo dolor sodales malesuada ut cursus neque. Aliquam lorem dui, vulputate eget ante sed, rutrum sodales arcu. Proin ipsum ex, condimentum et urna vitae, efficitur finibus nunc. Nunc lacinia enim id risus elementum maximus. Pellentesque dui quam, condimentum in rutrum sed, fermentum vel massa. Donec in est quis mi pulvinar venenatis nec vel risus. Aliquam sodales est ut purus condimentum molestie. Sed eleifend dui quis risus commodo, vel mollis orci vehicula. Sed id metus risus. Pellentesque vel laoreet sem, a dignissim augue. Phasellus lacus urna, facilisis et ultrices sed, vulputate vel arcu.
                    </p>
                </article>

                <section class="recent_activities_wrapper">
                    <div class="activity-header pull-left">Recent Activities</div>
                    <div class="seperator-line pull-right">
                        <hr>
                    </div>
                    <div class="clearfix form-group"></div>

                    <article class="row">
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <div class="group-count">
                                My Groups: 90
                            </div>
                        </div>
                    </article>
                    <article class="">

                        <div id="recent_activity_container" class="hidden" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
                            <p class="no-data">There is no data available yet</p>
                        </div>

                    </article>
                </section>
            </div>

        </div>
    </div>
</div>
</div>
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
                    <div class="cancel">
                        <p>Cancel</p>
                    </div>
                    <div class="save disable">
                        <i class="fa fa-check"></i>
                        <span>Save</span>
                    </div>
                    <div class="browse">
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
        <img src="<?= Url::to('@web/img/background/cover-bg.png'); ?>"/>
        <div class="change-cover"><i class="fa fa-camera"></i> Edit cover image</div>
    </div>
    <div class="profile-picture pull-left">
        <div class="img-user text-center"><img src="<%= data.image %>"></div>
        <div class="change-profile">
            <i class="fa fa-camera"></i>
        </div>
    </div>
    <div class="user-details-wrapper">
        <div class="user-details pull-left">
            <div class="user-name"><%= data.username %>, <%= data.year_old %></div>
            <div class="user-location">Bloomington, Indiana, U.S.A.</div>
        </div>
        <div class="btn-group profile-dropdown pull-left" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-gears"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class=''><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
                <li class=''><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
                <li class=''><a><i class='fa fa-user'></i> My profile info</a></li>
                <li class=''><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>

            </ul>
        </div>
        <div class="brillant pull-left">
            <div class="count">
                <span>0</span>
            </div>
        </div>
    </div>
</script>
<script id="profile_group_info" type="text/x-underscore-template">
    <div class="table-responsive group-details activity-details">
        <table class="table no-border">
            <% if(groups.length < 0) {%>
            <thead>
            <tr>
                <th class="col-xs-8"></th>
                <th class="col-xs-4"></th>
            </tr>
            </thead>
            <tbody>
            <% _.each(groups,function(group){ %>
            <tr>
                <td><a href="javascript:" class="title"><b><%= group.name %></b></a></td>
                <td class="group-actions text-right">
                    <% if (group.owner) { %>
                    <a href="javascript:" class=""><i class="fa fa-edit"></i><span>Edit</span></a>
                    <a href="javascript:" class=""><i class="fa fa-trash-o"></i><span>Delete</span></a>
                    <% } %>
                        <span class="date-details">
                            <%= group.created_at %>
                        </span>
                </td>
            </tr>
            <% }); %>
            </tbody>
            <% } else {%>
            <thead>
            <tr>
                <th class="col-xs-12"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="alert alert-info">There is no groups data available yet</div>
                </td>
            </tr>
            </tbody>
            <% } %>
        </table>
    </div>
</script>

<script id="profile_topic_info" type="text/x-underscore-template">
    <div class="table-responsive topic-details activity-details">
        <table class="table no-border">
            <% if(topics.length > 0) {%>
            <thead>
            <tr>
                <th class="col-xs-8"></th>
                <th class="col-xs-4"></th>
            </tr>
            </thead>
            <tbody>
            <% _.each(topics,function(group){ %>
            <tr>
                <td><a href="javascript:" class="title"><b><%= group.title %></b></a></td>
                <td class="topic-actions text-right">
                    <a href="javascript:" class=""><i class="fa fa-edit"></i><span>Edit</span></a>
                    <a href="javascript:" class=""><i class="fa fa-trash-o"></i><span>Delete</span></a>
                        <span class="date-details">
                            <%= group.created_at %>
                        </span>
                </td>
            </tr>
            <% }); %>
            </tbody>
            <% } else {%>
            <thead>
            <tr>
                <th class="col-xs-12"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="alert alert-info">There is no topics you created yet</div>
                </td>
            </tr>
            </tbody>
            <% } %>
        </table>
    </div>
</script>


<script id="profile_post_info" type="text/x-underscore-template">
    <div class="table-responsive post-details activity-details">
        <table class="table no-border">
            <% if(posts.length > 0) {%>
            <thead>
            <tr>
                <th class="col-xs-8"></th>
                <th class="col-xs-4"></th>
            </tr>
            </thead>
            <tbody>
            <% _.each(posts,function(item){ %>
            <tr>
                <td>
                    <div class="title"><a href="javascript:"><b><%= item.title %></b></a></div>
                    <div class="post-content"><%= item.content %></div>
                </td>
                <td class="text-right">
                    <div class="date-details"><%

                        print(item.created_at)
                        %></div>
                    <div class="post-actions text-right">
                        <a href="javascript:" class="post-edit"><i class="fa fa-edit"></i><span>Edit</span></a>
                        <a href="javascript:" class=""><i class="fa fa-trash-o"></i><span>Delete</span></a>
                    </div>
                </td>
            </tr>
            <% }); %>
            </tbody>
            <% } else {%>
            <thead>
            <tr>
                <th class="col-xs-12"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="alert alert-info">There is no posts you created yet</div>
                </td>
            </tr>
            </tbody>
            <% } %>
        </table>
    </div>
</script>



