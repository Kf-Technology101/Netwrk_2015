<?php use yii\helpers\Url; ?>
<div id="post_chat" data-topic="<?= $post->topic->id ?>">
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title">
                <span><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a></span>
                <span><i class="fa fa-angle-right"></i>DogsDogsDogsDog</span>
                <span><i class="fa fa-angle-right"></i> General DogsDogsDogsDogsDogs</span>
            </span>
        </div>
    </div>

    <div class="container_post_chat">
        <div class="message_send message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
            <div class="user_thumbnail">
                <div class="avatar">
                    <img src="">
                </div>
            </div>
            <div class="content_message">
                <p>Description of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word Help</p>
                <p class="time">20:30</p>
            </div>      
        </div>
        <div class="message_receiver message" data-img="<?= Url::to('@web/img/icon/timehdpi.png'); ?>">
            <div class="user_thumbnail">
                <div class="avatar">
                    <img src="">
                </div>
            </div>
            <div class="content_message">
                <p>Description of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word HelpDescription of the "Lorem ipsum dolor sit amet" text that appears in Word Help</p>
                <p class="time">20:30</p>
            </div>  
        </div>
    </div>
    <div class="nav_input_message">
        <div class="send_message input-group">
            <textarea type="text" class="form-control" placeholder="Type message here..."></textarea>
            <div class="input-group-addon paper"><i class="fa fa-paperclip"></i></div>
            <div class="input-group-addon emoji"><i class="fa fa-smile-o"></i></div>
            <div class="input-group-addon send" id="sizing-addon2">Send</div>
        </div>
    </div>
</div>