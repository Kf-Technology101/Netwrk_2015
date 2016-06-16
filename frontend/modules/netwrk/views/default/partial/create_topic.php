<?php use yii\helpers\Url; ?>
<div class="modal" id='create_topic_modal'>
    <!-- <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div> -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="name_user">
                        <p> Build a channel and add lines to it</p>
                    </div>
                </div>
                <div class="scrumb">
                    <p class="zipcode"></p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic">
                    <div class="topic">
                        <p class="title"> Channel </p>
                        <input type="text" class="name_topic" maxlength="128" placeholder="Channel Title">
                    </div>
                    <div class="post">
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