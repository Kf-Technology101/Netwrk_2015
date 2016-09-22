<?php 
    use yii\helpers\Url;
?>
<div id="cover-page">
	<span class="error hidden">Sorry my friend, you shall not pass</span><br/>
	<img src="<?= Url::to('@web/img/icon/netwrk-logo-blue_large.png'); ?>" id="coverImg">
    <div class="input-group form-group">
        <button class="btn btn-primary btn-lg share-location-btn">Share your location to join the Netwrk around you</button>
    </div>
    <p class="or-text">Or</p>
    <div class="input-group" id="inputGroup">
        <input type="text" class="form-control cover-input" id="cv-location" placeholder="Enter your city or zip code to see local news.">
        <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
    </div>
    <!--<div class="input-group hide" id="inputGroup">
        <input type="password" class="form-control cover-input" id="cv-password" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;What is the password?">
        <span class="input-group-addon"><i class="fa fa-key"></i></span>
    </div>-->
    <?= $this->render('@frontend/modules/netwrk/views/search/cover-result') ?>
</div>