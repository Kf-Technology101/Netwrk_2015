<?php 
    use yii\helpers\Url;
?>
<div id="cover-page">
	<span class="error hidden">Sorry my friend, you shall not pass</span><br/>
	<img src="<?= Url::to('@web/img/icon/netwrk-logo-blue_large.png'); ?>" id="coverImg">
	<div class="input-group hide" id="inputGroup">
        <input type="password" class="form-control" id="cv-password" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;What is the password?">
        <span class="input-group-addon"><i class="fa fa-key"></i></span>
    </div>
</div>