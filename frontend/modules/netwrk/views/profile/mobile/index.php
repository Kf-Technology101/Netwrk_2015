<?php use yii\helpers\Url; ?>

<div id="Profile" data-topic="<?= $topic->id ?>" <?php if (!empty($city)) { ?>data-city="<?= $city ?>"<?php } ?>>
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title">Profile</span>
        </div>
        <div class="setting">
            <span><i class="fa fa-gears"></i></span>
        </div>
    </div>

</div>

