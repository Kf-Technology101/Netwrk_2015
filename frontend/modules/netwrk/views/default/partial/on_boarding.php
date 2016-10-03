<?php use yii\helpers\Url; ?>
<div class="modal on-boarding" id='modalOnBoarding'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="title select-lines">Select a line to begin</div>
				<div class="title select-picture hidden">Upload profile picture to begin</div>
			</div>
			<div class="modal-body">
				<div class="lines-wrapper select-lines">
					<ul>
					</ul>
					<input type="hidden" name="selected-lines" class="selected-lines" value="0"/>
				</div>
				<div class="profile-picture-wrapper select-picture hidden">
					<div class="image-preview">
						<p>IMAGE PREVIEW</p>
						<div class="preview_img"></div>
						<div class="preview_img_ie"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="skip-btn">Skip</div>
				<div class="btn btn-default btn-save-lines disabled select-lines">Save</div>
				<div class="profile-picture-btn-control select-picture hidden">
					<div class="btn-img cancel hide">
						<p>Cancel</p>
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
							<input type="file" id="input_image" name='image' accept="image/jpg,image/png,image/jpeg,image/gif">
						<?php \yii\widgets\ActiveForm::end(); ?>
						<p>Browse</p>
					</div>
					<div class="btn-img save disable">
						<i class="fa fa-check"></i>
						<span>Save</span>
					</div>
				</div>
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