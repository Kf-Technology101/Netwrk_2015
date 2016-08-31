<?php use yii\helpers\Url; ?>
<div id='profile-slider' class='profile-slider modal-profile'>
	<div class="profile-slider-wrapper">
		<div class="profile-container">
			<div class="profile-info">

			</div>
			<div class="profile-activity-wrapper">
				<div class="panel-group" id="profile-accordion">
				<div class="panel panel-default">
					<div class="panel-heading top-header">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#profile-accordion" href="#followed">
								<p class="lp-title">Followed</p>
							</a>
						</h4>
					</div>
					<div id="followed" class="panel-collapse collapse in">
						<div class="panel-body">
							<article class="fav-communities_content-wrapper">

							</article>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading top-header">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#profile-accordion" href="#recent"><p class="lp-title">Recent</p></a>
						</h4>
					</div>
					<div id="recent" class="panel-collapse collapse">
						<div class="panel-body">
							<article class="recent-communities_content-wrapper">

							</article>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading top-header">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#profile-accordion" href="#recent-activities-groups">
								<p class="lp-title">Recent Activities : Groups</p>
							</a>
						</h4>
					</div>
					<div id="recent-activities-groups" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="group-count">
								My Groups: 90
							</div>
							<article id="recent_activity_container_groups" class="hidden">
								<p class="no-data">There is no data available yet</p>
							</article>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading top-header">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#profile-accordion" href="#recent-activities-channels">
								<p class="lp-title">Recent Activities : Channels</p>
							</a>
						</h4>
					</div>
					<div id="recent-activities-channels" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="group-count">
								My Groups: 90
							</div>
							<article id="recent_activity_container_topics" class="hidden">
								<p class="no-data">There is no data available yet</p>
							</article>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading top-header">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#profile-accordion" href="#recent-activities-lines">
								<p class="lp-title">Recent Activities : Lines</p>
							</a>
						</h4>
					</div>
					<div id="recent-activities-lines" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="group-count">
								My Groups: 90
							</div>
							<article id="recent_activity_container_posts" class="hidden">
								<p class="no-data">There is no data available yet</p>
							</article>
						</div>
					</div>
				</div>
			</div>
			</div>
			<!--<div class="profile-activity-wrapper">
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
						<div class="col-sm-6">
							<div role="group" class="btn-group btn-group-default navigation-btn-group">
								<button class="btn btn-default group" type="button" id="">
									<span>Groups</span>
								</button>
								<button class="btn btn-default topic" type="button" id="">
									<span>Channels</span>
								</button>
								<button class="btn btn-default post" type="button" id="">
									<span>Lines</span>
								</button>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="group-count">
								My Groups: 90
							</div>
						</div>
					</article>
					<article id="recent_activity_container" class="hidden">
						<p class="no-data">There is no data available yet</p>
					</article>

				</section>
			</div>-->
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
		<img src="<?= Url::to('@web/img/background/cover-bg.png'); ?>"/>
		<div class="change-cover"><i class="fa fa-camera"></i> Edit cover image</div>
		<span></span>
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
			<div class="user-location"><%= data.city %>, <%= data.state %>, <%= data.country %></div>
		</div>
		<div class="btn-group profile-dropdown pull-left" role="group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-gears"></i>
			</button>
			<ul class="dropdown-menu dropdown-menu-right">
				<li class=''><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
				<li class=''><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
				<li class=''><a href="javascript:" id="my_profile_info"><i class='fa fa-user'></i> My profile info</a></li>
				<li class=''><a href="javascript:" id="my_profile_edit"><i class='fa fa-user'></i> Edit Profile</a></li>
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
								<div class="col-xs-8">
									<a href="javascript:" class="title group-trigger"
									   data-id="<%= item.id %>"
									   data-city-id="<%= item.city_id %>"
									   data-city-zip="<%= item.city_zip%>">
										<b><%= item.name %></b>
									</a>
								</div>
								<div class="col-xs-4">
									<div class="topic-actions text-right">
										<a href="javascript:" class="edit-group" data-id="<%= item.id %>" data-city_id="<%= item.city_id %>"><i class="fa fa-edit"></i><span>Edit</span></a>
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
			<div class="group-item text-center">
				<h1>Come back later</h1>
				<!--<div class="alert alert-info">You haven't created any group yet. Please check out any community and create a group.</div>-->
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
							<div class="col-xs-8">
								<a href="javascript:" class="title topic-trigger" data-value="<%= item.id %>" data-city="<%= item.city_id %>" data-city-name="<%= item.city_name %>"><b><%= item.title %></b></a>
							</div>
							<div class="col-xs-4">
								<div class="topic-actions text-right">
									<a href="javascript:" class="edit-topic" data-id="<%= item.id%>" data-city="<%= item.city_id %>" data-city_name="<%= item.city_name %>"><i class="fa fa-edit"></i><span>Edit</span></a>
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
			<div class="alert alert-info">You haven't created any channel yet. Please check out any community and create a channel.</div>
		</div>
		<% } %>
	</div>
</script>

<script id="profile_post_info" type="text/x-underscore-template">
	<div class="post-details activity-details recentActivityPosts" id="recentActivityPosts">
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
							<div class="col-xs-8 post">
								<p class="post-title"><%= item.title %></p>
								<div class="post-content"><%= item.content %></div>
							</div>
							<div class="col-xs-4 text-right">
								<div class="date-details">
									<% print(item.formatted_created_date) %>
								</div>
								<div class="post-actions">
									<a href="javascript:" class="post-edit" data-id="<%= item.id %>"><i class="fa fa-edit"></i><span>Edit</span></a>
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
			<div class="alert alert-info">You haven't created any line yet. Please check out any community and create a line.</div>
		</div>
		<% } %>
	</div>
</script>

<script id="profile_fav-communities_template" type="text/x-underscore-template">
	<div class="clearfix">
		<div class="communities-list" id="favoriteCommunities">
			<% if(!_.isEmpty(items)) {%>
			<% _.each(items, function(item, key){ %>
			<div class="community">
                        <span class="zip-code pull-left">
                            <a class="community-modal-trigger"
							   href="javascript:"
							   data-lat="<%= item.lat %>"
							   data-lng="<%= item.lng %>"
							   data-city-id="<%= item.city_id %>"
							   title="">
								<% if(item.city_office_type != null) { %>
								<%= item.city_office %>
								<% } else { %>
								<%= item.city_name %>
								<% } %>
							</a>
                        </span>
                        <span class="community-action pull-right un-favorite-trigger"
							  data-object-type="<%= 'city' %>"
							  data-object-id="<%= item.city_id %>"><i class="fa fa-trash-o"></i></span>
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