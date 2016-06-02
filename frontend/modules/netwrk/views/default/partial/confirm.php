<?php use yii\helpers\Url; ?>
<div class="modal fade" id="confirmationBox" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div id="activationBox" class="signupBox clearfix" >
					<div class="activationWrapper">
						<div class="activationForm">
							<div class="alert alert-danger hidden"></div>
							<h4>Are you sure you want to delete this?</h4>
							<hr/>
							<div class="buttonWrap text-center">
								<button id="btnYes" type="submit" name="btnYes" class="btn btn-default btn-md" value="Send"> Yes </button>
								<button id="btnNo" type="submit" name="btnCancel" class="btn btn-default btn-md" data-dismiss="modal" value="Send"> No </button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>