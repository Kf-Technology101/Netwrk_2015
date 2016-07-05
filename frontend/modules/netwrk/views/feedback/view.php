<?php use yii\helpers\Url; ?>
<div class="feedback-section hide">
    <section class="feedback-head">
        <span class="title">Feedback</span>
        <span class="close feedback-close-trigger">
            <span class="circle-close">&times;</span> Close
        </span>
    </section>
    <section class="feedback-content" data-parent="" data-object="" data-id="">
        <?php
            if(Yii::$app->user->isGuest) {
                $option_class = 'login-trigger';
            } else {
                $option_class = 'feedback-option-trigger';
            }
        ?>
        <ul class="list-unstyled text-center feedback-list">
            <li>
                <a href="javascript:" class="<?php echo $option_class;?> text-center" data-option="like" data-point="+15">
                    <i class="ci-feedback-option-1"></i>
                </a>
            </li>
            <li>
                <a href="javascript:" class="<?php echo $option_class;?>" data-option="funny" data-point="+5">
                    <i class="ci-feedback-option-2"></i>
                </a>
            </li>
            <li>
                <a href="javascript:" class="<?php echo $option_class;?>" data-option="important" data-point="+5">
                    Important angle
                </a>
            </li>
            <li>
                <a href="javascript:" class="<?php echo $option_class;?>" data-option="constructive" data-point="-2">
                    Be more constructive
                </a>
            </li>
            <li>
                <a href="javascript:" class="<?php echo $option_class;?>" data-option="vulgar" data-point="-5">
                    Vulgar
                </a>
            </li>
            <li>
                <a href="javascript:" class="<?php echo $option_class;?>" data-option="block" data-point="-15">
                    Block
                </a>
            </li>
        </ul>
    </section>
</div>