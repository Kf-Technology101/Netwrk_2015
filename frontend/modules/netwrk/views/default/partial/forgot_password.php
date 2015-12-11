<?php use yii\helpers\Url; ?>
<!-- <button class="btn btn-default btn-sm" data-toggle="modal" data-target=".forgotPassword">forgot password</button> -->
<div class="modal fade forgotPassword" id='forgot-password'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="title">
                        <p> Forgot your password</p>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <p class="description">Please enter the email address associated and we will send you an email with a link to reset your password</p>
                <!-- <form data-toggle="validator" role="form">
                    <div class="form-group">
                        <input type="email" class="form-control email" name="email" id="inputEmail" placeholder="Email address" data-error="Bruh, that email address is invalid" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <button type="submit" class="send-email disable">Send Email</button>
                </form> -->

                <input type="email" class="form-control email" name="email" placeholder="Email address">
                <div class="send-email disable">Send Email</div>
            </div>
        </div>
    </div>
</div>