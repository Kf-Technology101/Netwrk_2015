<?php
	use yii\helpers\Url;
	use yii\web\Cookie;
	$cookies = Yii::$app->request->cookies;
?>
<div id='netwrkNavigation' class='netwrk-navigation'>
	<div class="netwrk-news-trigger custom-btn btn-netwrk-news">Netwrk News</div>
	<div class="most-active-trigger custom-btn btn-most-active">Most Active</div>
	<div class="your-netwrk-wrapper">
		<div class="title">Your Netwrk</div>
		<div class="your-netwrks">

		</div>
	</div>
	<?php if (Yii::$app->user->isGuest):?>
		<div id="navProfileWrapper" class="login-trigger custom-btn btn-profile">Login</div>
	<?php endif; ?>
</div>

