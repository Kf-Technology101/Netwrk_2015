<?php use yii\helpers\Url; ?>
<div class="modal" id='create_post'>
    <!-- <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div> -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="name_user">
                        <p> Add a line</p>
                    </div>
                </div>
                <div class="scrumb">
<!--                     <div class="logo">
                        <img src="<?#= Url::to('@web/img/icon/netwrk-logo.png'); ?>">
                    </div>
                    <p class="break"> > </p> -->
                    <p class="zipcode"> 46975 </p>
                    <p class="break"> > </p>
                    <p class="topic"> Discussion over Democratic Primary </p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic">
                    <div class="post">
                        <input type="hidden" name="post_id" id="post_id" value=""/>
                        <div class="post-title">
                            <p class="title"> Line </p>
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">#</span>
                                <input type="text" class="name_post" maxlength="128" placeholder="Head-line">
                            </div>
                        </div>
                        <div class="post-message">
                            <p class="title"> Message </p>
                            <textarea class="message" placeholder="Don't be shy! Say something!" maxlength="1024"></textarea>
                        </div>
                    </div>
                    <div class="btn-control">
                    <div class="cancel disable">
                            <p>Reset</p>
                        </div>
                        <div class="save disable">
                            <span>Save</span>
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>