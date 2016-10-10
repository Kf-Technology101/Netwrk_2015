<?php use yii\helpers\Url; ?>
<script id="blue_dot_maker_posts" type="text/x-underscore-template">
	<div class="top-post-brilliant">
		<% _.each(marker.posts,function(e){ %>
			<div class="item-post">
				<p class="name-post" data-value="<%= e.post_id %>"
				   data-type="<%= e.post_type %>"
				   data-name="<%= e.name_post %>"
				   data-content="<%= e.content %>"><%= e.name_post %></p>
				<!--<p class="num-post"><%= e.brilliant %></p>-->
			</div>
		<% })%>
	</div>
</script>

<div style="display:none;">
	<div id="blueDotInfoWindow">
		<div id="iw-container" class="cgm-container" onmouseleave="Map.mouseOutsideInfoWindow();" onmouseenter="Map.mouseInsideInfoWindow();" style="max-width: 260px; padding: 20px 0 0;">
			<div class="iw-content">
				<!--<div class="iw-subTitle text-right" onclick="Map.closeAllInfoWindows();">
					<span class="close" style="font-size: 25px; line-height: 14px;"><span aria-hidden="true">&times;</span></span>
				</div>-->
				<div class="iw-subTitle location-details-wrapper text-left">
					<div id="formattedAddress" class="formatted-address"><i class="fa fa-map-marker"></i><span class="location" id="formattedLocation"></span> </div>
					<h4 class="location-details hide">Welcome to <span id="blueDotLocation"><span>requesting...</span></span></h4>
					<!--<h5 class="discussion-title">Active Lines near <img src="/img/icon/pale-blue-dot.png" height="20" width="20"/> </h5>
					<div id="discussionWrapper" class="discussion-wrapper"></div>-->
				</div>
				<div id="communityInfo" class="clearfix">
					<!--<div class="row">
						<div class="col-xs-6 dot-info-main">
							<div class="dot-info-wrapper">
								<a id="btnMyLocation" class="btn_my_location" href="javascript:" onclick="Map.getMyHomeLocation(Map.map, 'build');">
									<i class="fa fa-plus build-icon"></i>
									<span class="blue-dot-lable">Anywhere</span>
								</a>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="row dot-info-main">
								<div class="iw-subTitle  dot-info-wrapper">Hold <img src="/img/icon/pale-blue-dot.png"/> to move &nbsp;&nbsp;&nbsp;</div>
								<div class="iw-subTitle  dot-info-wrapper zoom-info hide">Click <img src="/img/icon/pale-blue-dot.png"/> to zoom in</div>
								<div class="iw-subTitle  dot-info-wrapper zoom-cancel">Click <img src="/img/icon/pale-blue-dot.png"/> to Cancel</div>
							</div>
						</div>
					</div>
					<div class="dot-info-main double-click">
						<div class="iw-subTitle row dot-info-wrapper">Double click map to zoom & access channels</div>
					</div>-->
					<!--<div class="iw-subTitle" id="cm-coords"></div>-->
					<div class="create-section-wrapper">
						<div class="iw-subTitle col-xs-12 create-section" id="actionBuildCommunity" style="padding: 5px 0 0;"><a href="javascript:" class="create-button line-button" onclick="Map.CreateLocationPost(Map.blueDotLocation.zipcode);"><span>Build a Line</span></a></div>
						<div class="iw-subTitle col-xs-12 create-section hide" id="actionJoinCommunity" style="padding: 5px 0 0;"><a href="javascript:" class="create-button join-button" onclick="Map.joinCommunity(Map.blueDotLocation.community);"><span>Join this netwrk</span></a></div>
						<!--<div class="iw-subTitle col-xs-6 create-section" id="actionBuildCommunity"><a href="javascript:" class="create-button group-button" onclick="Map.CreateLocationGroup(Map.blueDotLocation.zipcode);"><span>Create a Group</span></a></div>-->
						<!--<div class="iw-subTitle col-xs-6 create-section" id="actionHaveParty"><a href="javascript:" class="create-button channel-button" onclick="Map.CreateLocationTopic(Map.blueDotLocation.zipcode);"><span class="">Create a Channel</span></a></div>-->
					</div>
					<!--<div class="iw-subTitle" id="cm-zip">Zip: <span>requesting...</span></div>-->
					<!--<div class="iw-subTitle col-xs-12"><span class="post-title">
					<a id="my-location" class="my-location" href="javascript:" onclick="Map.zoomMap(Map.blueDotLocation.lat, Map.blueDotLocation.lon, Map.blueDotLocation.zoomMiddle, Map.map);"><h5>Build</h5></a>
					</span></div>
					<!--<div class="iw-subTitle"><span class="post-title">
					<a id="create-location-group" data-zipcode="" class="a-create-group create-location-group hidden" href="javascript:" onclick="Map.CreateLocationGroup(Map.blueDotLocation.zipcode);"><h4>Place your topic here</h4></a>
					</span></div>-->
					<!--<div class="iw-subTitle col-xs-12 show-area-topic-section">
						<a id="show-area-topic" data-zipcode="" class="show-area-topic" href="javascript:" onclick="Map.showTopicFromZipcode(Map.blueDotLocation.zipcode);"><span>Go to the local community center</span></a>
					</div>-->
				</div>
				<div id="noCommunity">
					<div class="dot-info-main">
						<div class="iw-subTitle dot-info-wrapper">Currently there are no communities for this area!</div>
					</div>
				</div>
			</div>
			<div class="iw-bottom-gradient"></div>
		</div>
	</div>
</div>

<div style="display:none;">
	<div id="createInfoWindow">
		<div id="ciw-container" class="cgm-container ciw-container" onmouseleave="Map.mouseOutsideInfoWindow();" onmouseenter="Map.mouseInsideInfoWindow();">
			<div class="iw-content">
				<div class="iw-subTitle text-right" onclick="Map.closeAllInfoWindows();">
					<span class="close"><span aria-hidden="true">&times;</span></span>
				</div>
				<div class="iw-subTitle location-details-wrapper text-left">
					<h4 class="location-details">Welcome to <span id="clickLocation"><span>requesting...</span></span></h4>
				</div>
				<div id="communityInfoCreate" class="clearfix">
					<div class="click-location-create-section-wrapper">
						<div class="iw-subTitle col-xs-6 create-section" id="actionBuildCommunity"><a href="javascript:" class="create-button group-button" onclick="Map.clickLocationCreateGroup(Map.clickLocation.zipCode);"><span>Create a Group</span></a></div>
						<div class="iw-subTitle col-xs-6 create-section" id="actionHaveParty"><a href="javascript:" class="create-button channel-button" onclick="Map.clickLocationCreateTopic(Map.clickLocation.zipCode);"><span class="">Create a Channel</span></a></div>
					</div>
				</div>
				<div id="noCommunityCreate" class="hide">
					<div class="dot-info-main">
						<div class="iw-subTitle dot-info-wrapper">Currently there are no communities for this area!</div>
					</div>
				</div>
			</div>
			<div class="iw-bottom-gradient"></div>
		</div>
	</div>
</div>

<div style="display: none">
	<div id="userLocationInfoWindow">
		<div id="uiw-container" class="cgm-container" style="max-width: 260px; padding: 0px;">
			<div class="uiw-content">
				<div class="iw-subTitle text-right" onclick="Map.closeUserLocationInfoWindow();" style="position: absolute;right: 3px;top: 2px;">
					<span class="close" style="font-size: 20px;color: #fff;line-height: 14px;"><span aria-hidden="true">&times;</span></span>
				</div>
				<div class="location-image-wrapper hide" id="userLocationImage" style="height:auto;max-height: 120px;overflow: hidden;">
					<img src="" alt="" style="height: auto;max-width: 100%;width: 100%;"/>
				</div>
				<div class="iw-subTitle text-center">
					<h4 class="" style="font-size: 20px; margin-bottom: 10px;">Welcome to <span id="userLocation"><span>requesting...</span></span></h4>
					<div class="sub-title build-content">
						<div class="iw-subTitle" style="margin-bottom: 10px">Click anywhere to build or access a chat line</div>
						<!--<div class="" style="color: red;margin-bottom: 2px;">or</div>-->
					</div>
				</div>
				<div id="noCommunityUserLocation" class="hide">
					<div class="dot-info-main">
						<div class="iw-subTitle dot-info-wrapper">Currently there are no communities for this area!</div>
					</div>
				</div>
				<div class="clearfix">
					<div class="create-section-wrapper">
						<div class="iw-subTitle col-xs-12 create-section" style="padding: 0">
							<div class="join-content hide">
								<a href="javascript:" style="padding: 0; line-height: 40px;" class="create-button join-button" onclick="Map.joinCommunity(Map.blueDotLocation.community, 'user-location');"><span>Join this netwrk</span></a>
							</div>
							<!--<div class="build-content hide">
								<a href="javascript:" class="create-button line-button" onclick="Map.CreateUserLocationPost();"><span>Build a Line at your location</span></a>
							</div>-->
						</div>
					</div>
				</div>
			</div>
			<div class="iw-bottom-gradient"></div>
		</div>
	</div>
</div>