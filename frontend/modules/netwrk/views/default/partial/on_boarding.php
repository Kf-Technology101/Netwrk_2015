<?php use yii\helpers\Url; ?>
<div class="modal on-boarding" id='modalOnBoarding'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="title">Select a line to begin</div>
			</div>
			<div class="modal-body">
				<div class="lines-wrapper">
					<ul>
					</ul>
					<input type="hidden" name="selected-lines" class="selected-lines" value="0"/>
				</div>
				<div class="profile-picture-wrapper hidden"></div>
			</div>
			<div class="modal-footer">
				<div class="btn btn-default btn-save-lines disabled">Save</div>
			</div>
		</div>
	</div>

</div>
<script id="boarding_lines" type="text/x-underscore-template">
	<%
		_.each(top_post,function(e,i){
		%>
			<li class="line-list-item" data-post-id="<%= e.id %>" data-user="<%= e.user_id %>">
				<div class='line-row'>
					<span class='avatar-user'>
						<img class='img_avatar' src="<%= e.photo %>" />
					</span>
					<div class='title-description-user'>
						<div class='title-chat-inbox'><%= e.title %></div>
						<div class='description-chat-inbox'><%= e.content%></div>
					</div>
					<span class="line-check-selected hide">
						<i class='fa fa-2x fa-check-circle check-circle'></i>
					</span>
					<i class='fa fa-2x fa-angle-right'></i>
				</div>
			</li>
			<%
		});
	%>
</script>