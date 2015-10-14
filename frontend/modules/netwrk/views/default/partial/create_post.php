<?php use yii\helpers\Url; ?>
<div class="modal" id='create_post'>
    <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/netwrk_btn.png'); ?>"/></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="back_page">
                        <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>">
                    </div>
                    <div class="name_user">
                        <p> Create a Post</p>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic">
                    <div class="post">
                        <p class="title"> Post </p>
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2">#</span>
                            <input type="text" class="name_post" maxlength="128" placeholder="Post title">
                        </div>
                        <p class="title"> Message </p>
                        <textarea class="message" placeholder="Message..." maxlength="1024"></textarea>
                    </div>
                    <div class="btn-control">
                        <div class="cancel disable">
                            <p>Reset</p>
                        </div>
                        <div class="save disable">
                            <i class="fa fa-check"></i>
                            <span>Save</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>