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
		<div id="iw-container" class="cgm-container" onmouseleave="Map.mouseOutsideInfoWindow();" onmouseenter="Map.mouseInsideInfoWindow();">
			<div class="iw-content">
				<div class="iw-subTitle location-details-wrapper text-left">
					<h4 class="location-details">You are in: <span id="blueDotLocation"><span>requesting...</span></span></h4>
					<h5 class="discussion-title">Active Party Lines near <img src="/img/icon/pale-blue-dot.png" height="20" width="20"/> </h5>
					<div id="discussionWrapper" class="discussion-wrapper"></div>
				</div>
				<div id="communityInfo">
					<div class="row dot-info-main">
						<div class="iw-subTitle col-xs-6 dot-info-wrapper">Hold <img src="/img/icon/pale-blue-dot.png"/> to move &nbsp;&nbsp;&nbsp;</div>
						<div class="iw-subTitle col-xs-6 dot-info-wrapper zoom-info hide">Click <img src="/img/icon/pale-blue-dot.png"/> to zoom in</div>
						<div class="iw-subTitle col-xs-6 dot-info-wrapper zoom-cancel">Click <img src="/img/icon/pale-blue-dot.png"/> to Cancel</div>
					</div>
					<div class="dot-info-main double-click">
						<div class="iw-subTitle row dot-info-wrapper">Double click map to zoom & access channels</div>
					</div>
					<!--<div class="iw-subTitle" id="cm-coords"></div>-->
					<div class="create-section-wrapper hide">
						<div class="iw-subTitle col-xs-6 create-section" id="actionBuildCommunity"><a href="javascript:" class="create-button group-button" onclick="Map.CreateLocationGroup(Map.blueDotLocation.zipcode);"><span>Create a Group</span></a></div>
						<div class="iw-subTitle col-xs-6 create-section" id="actionHaveParty"><a href="javascript:" class="create-button channel-button" onclick="Map.CreateLocationTopic(Map.blueDotLocation.zipcode);"><span class="">Create a Channel</span></a></div>
					</div>
					<!--<div class="iw-subTitle" id="cm-zip">Zip: <span>requesting...</span></div>-->
					<div class="iw-subTitle col-xs-12"><span class="post-title">
					<a id="my-location" class="my-location" href="javascript:" onclick="Map.zoomMap(Map.blueDotLocation.lat, Map.blueDotLocation.lon, Map.blueDotLocation.zoomMiddle, Map.map);"><h5>Build</h5></a>
					</span></div>
					<!--<div class="iw-subTitle"><span class="post-title">
					<a id="create-location-group" data-zipcode="" class="a-create-group create-location-group hidden" href="javascript:" onclick="Map.CreateLocationGroup(Map.blueDotLocation.zipcode);"><h4>Place your topic here</h4></a>
					</span></div>-->
					<div class="iw-subTitle col-xs-12 show-area-topic-section">
						<a id="show-area-topic" data-zipcode="" class="show-area-topic" href="javascript:" onclick="Map.showTopicFromZipcode(Map.blueDotLocation.zipcode);"><span>Go to area page</span></a>
					</div>
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