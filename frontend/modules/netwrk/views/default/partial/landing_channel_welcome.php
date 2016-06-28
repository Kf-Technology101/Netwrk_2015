<?php use yii\helpers\Url; ?>
<div class="modal landing-welcome-modal channel-welcome-modal" id="modal_landing_channel_welcome" role='dialog'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h4>Anyone can chat in a line.<br/> Housed in channels, they can be found or built anywhere.<br/> All we ask is...</h4>
				<!--<h4>Click build to create your own, back to see the main channel, or join the area discussion here!</h4>
				<h4>Whatever you do</h4>-->
				<h1>Spread</h1>
				<img src="<?= Url::to('@web/img/icon/meet-icon-blue.png'); ?>">
			</div>
		</div>
	</div>
</div>
