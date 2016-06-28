<?php use yii\helpers\Url; ?>
<div class="feedback-section hide">
    <section class="feedback-head">
        <span class="title">Feedback</span>
        <span class="close feedback-close-trigger">
            <span class="circle-close">&times;</span> Close
        </span>
    </section>
    <section class="feedback-content" data-parent="" data-object="" data-id="">
        <ul class="list-unstyled text-center">
            <li>
                <a href="javascript:" class="feedback-option-trigger" data-option="like" data-point="+15">
                    <img src="<?= Url::to('@web/img/icon/brilliant_off.png'); ?>" />
                </a>
            </li>
            <li>
                <a href="javascript:" class="feedback-option-trigger" data-option="funny" data-point="+5">
                    Funny
                </a>
            </li>
            <li>
                <a href="javascript:" class="feedback-option-trigger" data-option="constructive" data-point="-2">
                    Be more constructive
                </a>
            </li>
            <li>
                <a href="javascript:" class="feedback-option-trigger" data-option="vulgar" data-point="-5">
                    Vulgar
                </a>
            </li>
            <li>
                <a href="javascript:" class="feedback-option-trigger" data-option="block" data-point="-15">
                    Block
                </a>
            </li>
        </ul>
    </section>
</div>