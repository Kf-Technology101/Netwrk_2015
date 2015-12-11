<?php use yii\helpers\Url; ?>
<!-- <button class="btn btn-default btn-sm" data-toggle="modal" data-target=".resetPassword">reset password</button> -->
<div class="modal fade resetPassword" id='reset-password'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="title">
                        <p> Reset your password</p>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="title-field">New Password</label>
                    <input type="password" class="form-control new-pass" id="new-pass" placeholder="New Password">
                </div>
                <div class="form-group">
                    <label class="title-field">Confirm Password</label>
                    <input type="password" class="form-control confirm-pass" id="confirm-pass" placeholder="Confirm Password">
                </div>
                <div class="reset disable">Reset</div>
            </div>
        </div>
    </div>
</div>