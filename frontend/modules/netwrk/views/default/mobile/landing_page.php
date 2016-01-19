<?php use yii\helpers\Url; ?>
<div id='ld_modal_landing_page'>
	<div class="ld-modal-dialog">
		<div class="ld-modal-content">
			<div class="ld-modal-header">
				<div class="header">
					<div class="title">
						<p class="main-header">Welcome to <span>Netwrk</span></p>
						<p class="sub-header">Where we facilitate comunity communications.</p>
					</div>
				</div>
			</div>
			<div class="ld-modal-body">
				<!--top post-->
				<div class="top-post">
					<div class="top-header">
						<p class="lp-title">Top Post</p>
						<p class="lp-description">Check out some of the discussions on some of your favorite subjects</p>
					</div>	
					<div class="top-post-content">
						<div class="post-row">
							<div class="avatar">
								<img src="<?= Url::to('@web/img/icon/images.jpg'); ?>">
							</div>
							<div class="post">
								<p class="post-title">#Post about my dog</p>
								<div class="post-content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut accumsan turpis...
								</div>
							</div>
							<div class="action">
								<div class="chat"><i class="fa fa-comments"></i>Chat</div>
								<span class="brilliant">1K</span>							
							</div>
						</div>
						<div class="post-row">
							<div class="avatar">
								<img src="<?= Url::to('@web/img/icon/images.jpg'); ?>">
							</div>
							<div class="post">
								<p class="post-title">#Post about my dog</p>
								<div class="post-content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut accumsan turpis...
								</div>
							</div>
							<div class="action">
								<div class="chat"><i class="fa fa-comments"></i>Chat</div>
								<span class="brilliant">1K</span>							
							</div>
						</div>
						<div class="post-row last-row">
							<div class="avatar">
								<img src="<?= Url::to('@web/img/icon/images.jpg'); ?>">
							</div>
							<div class="post">
								<p class="post-title">#Post about my dog</p>
								<div class="post-content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut accumsan turpis...
								</div>
							</div>
							<div class="action">
								<div class="chat"><i class="fa fa-comments"></i>Chat</div>
								<span class="brilliant">1K</span>							
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<!--end top post content-->
				</div><!--end top post-->
				<!--top topic-->
				<div class="top-topic">
					<div class="top-header">
						<p class="lp-title">Top Topics</p>
						<p class="lp-description">Browse these topics of coversations</p>
					</div>					
					<div class="top-topic-content">
						<div class="topic-row">
							<p class="topic-title">General Discussion</p>
							<div class="post-counter"><span class="arrow"><i class="fa fa-angle-right"></i></span>75<i class="fa fa-file-text"></i></div>
              			</div>
              			<div class="topic-row">
							<p class="topic-title">General Discussion</p>
							<div class="post-counter"><span class="arrow"><i class="fa fa-angle-right"></i></span>75<i class="fa fa-file-text"></i></div>
              			</div>
						<div class="topic-row last-row">
							<p class="topic-title">Dogs</p>
							<div class="post-counter"><span class="arrow"><i class="fa fa-angle-right"></i></span>75<i class="fa fa-file-text"></i></div>
              			</div>
					</div><!--end top topic content-->
				</div><!--end top topic-->
				<!--top communities-->
				<div class="top-communities">
					<div class="top-header">
						<p class="lp-title">Top Communities</p>
						<p class="lp-description">These thoughts are building in 46202</p>
					</div>
					<div class="top-communities-content">
						<div class="communities-row">
							<div class="com-content">
								<p class="zipcode">46202</p>
								<p class="subtext">
									<span>Education</span>
									<span>Netwrk</span>
									<span>Company</span>
								</p>
							</div>
							<span class="arrow"><i class="fa fa-angle-right"></i></span>
						</div>
						<div class="communities-row">
							<div class="com-content">
								<p class="zipcode">38605</p>
								<p class="subtext">
									<span>Musics</span>
									<span>Singer</span>
									<span>Badman</span>
								</p>
							</div>
							<span class="arrow"><i class="fa fa-angle-right"></i></span>
						</div>
						<div class="communities-row">
							<div class="com-content">
								<p class="zipcode">47241</p>
								<p class="subtext">
									<span>Traffic</span>
									<span>Natural</span>
									<span>Animal</span>
								</p>
							</div>
							<span class="arrow"><i class="fa fa-angle-right"></i></span>
						</div>
					</div><!--end top communities content-->
				</div><!--end top communities-->
			</div>
		</div>
	</div>
	<div class="ld-modal-footer">
		<div class="landing-btn btn-meet">Meet</div>
		<div class="landing-btn btn-my-community">My Community</div>
		<div class="landing-btn btn-explore">Explore</div>
	</div>
</div>