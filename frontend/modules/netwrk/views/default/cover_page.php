<?php 
    use yii\helpers\Url;
?>
<div id="cover-page">
	<span class="error hidden">Sorry my friend, you shall not pass</span><br/>
	<img src="<?= Url::to('@web/img/icon/netwrk-logo-blue_large.png'); ?>" onload="$('#inputGroup').removeClass('hide');">
	<div class="input-group hide" id="inputGroup">
        <input type="text" class="form-control cover-input" id="cv-password" placeholder="Please enter zip code or city">
        <span class="input-group-addon"><i class="fa fa-key"></i></span>
    </div>
    <?= $this->render('@frontend/modules/netwrk/views/search/cover-result') ?>
</div>