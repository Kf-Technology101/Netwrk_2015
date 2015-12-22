<?php use yii\helpers\Url;?>
<div class="result-search">
	<div class="location" id="local">
		<div class="title">Local <span>(with in 50 miles)</span></div>
		<div class="content-result">
			<div class="post-result">
				<div class="post-item">
					<div class="thumb"><img src="<?= Url::to('@web/img/icon/no_avatar.jpg') ?>"></div>
					<div class="content-post">
						<p class="title">Post about my dog</p>
						<p class="container-post">loremloremloremloremloremloremloremlorem<a>show more</a></p>
					</div>
					<div class="info">
						<p class="date">7/12/2015</p>
						<div class="brillant">
							<p>50</p>
						</div>
					</div>
				</div>
				<div class="post-item">
					<div class="thumb"><img src="<?= Url::to('@web/img/icon/no_avatar.jpg') ?>"></div>
					<div class="content-post">
						<p class="title">Post about my dog</p>
						<p class="container-post">loremloremloremloremloremloremloremlorem...<a> show more</a></p>
					</div>
					<div class="info">
						<p class="date">7/12/2015</p>
						<div class="brillant">
							<p>50</p>
						</div>
					</div>
				</div>
			</div>
			<div class="topic-result">
				<div class="topic-item">
					<p class="topic-name"> Topic dog</p>
					<div class="count-post">
						<p>70<i class="fa fa-file-text"></i></p>
					</div>
					<span class="topic-arrow"><i class="fa fa-angle-right"></i></span>
				</div>
				<div class="topic-item">
					<p class="topic-name"> Topic dog</p>
					<div class="count-post">
						<p>70<i class="fa fa-file-text"></i></p>
					</div>
					<span class="topic-arrow"><i class="fa fa-angle-right"></i></span>
				</div>
			</div>
			<div class="netwrk-result">
				<div class="netwrk-item">
					<p class="netwrk-name">44244</p>
				</div>
			</div>
		</div>
	</div>
	<div class="location" id="global">
		<div class="title">Global</div>
		<div class="content-result">
			<div class="post-result"></div>
			<div class="topic-result"></div>
			<div class="netwrk-result"></div>
		</div>
	</div>
	<p class="notice">
		Not what your looking for? Bummer,try again with more detail or make it yourself
	</p>
</div>