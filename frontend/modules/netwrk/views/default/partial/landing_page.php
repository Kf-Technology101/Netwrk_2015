<?php use yii\helpers\Url; ?>
<div class="modal" id='modal_landing_page'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="header">
					<img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>">
					<div class="title">
						<p class="main-header">Welcome to <span>Netwrk</span></p>
						<p class="sub-header">Where we facilitate comunity communications.</p>
					</div>
				</div>
			</div>
			<div class="modal-body">
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
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut accumsan turpis eget ante ultricies viverra. Proin rutrum eros velit, eu mollis turpis semper non... <a href="#">Show more</a>
								</div>
							</div>
							<div class="action">
								<div class="chat"><i class="fa fa-comments"></i>Chat</div>
								<span class="brilliant">1K</span>							
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="post-row">
							<div class="avatar">
								<img src="<?= Url::to('@web/img/icon/images.jpg'); ?>">
							</div>
							<div class="post">
								<p class="post-title">#Post about my dog</p>
								<div class="post-content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut accumsan turpis eget ante ultricies viverra. Proin rutrum eros velit, eu mollis turpis semper non... <a href="#">Show more</a>
								</div>
							</div>
							<div class="action">
								<div class="chat"><i class="fa fa-comments"></i>Chat</div>
								<span class="brilliant">1K</span>							
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="post-row last-row">
							<div class="avatar">
								<img src="<?= Url::to('@web/img/icon/images.jpg'); ?>">
							</div>
							<div class="post">
								<p class="post-title">#Post about my dog</p>
								<div class="post-content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut accumsan turpis eget ante ultricies viverra. Proin rutrum eros velit, eu mollis turpis semper non... <a href="#">Show more</a>
								</div>
							</div>
							<div class="action">
								<div class="chat"><i class="fa fa-comments"></i>Chat</div>
								<span class="brilliant">1K</span>							
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
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
							<div class="post-counter">75<i class="fa fa-file-text"></i><span class="arrow">></span></div>
							<div class="clearfix"></div>
              			</div>
              			<div class="topic-row">
							<p class="topic-title">General Discussion</p>
							<div class="post-counter">75<i class="fa fa-file-text"></i><span class="arrow">></span></div>
							<div class="clearfix"></div>
              			</div>
						<div class="topic-row last-row">
							<p class="topic-title">Dogs</p>
							<div class="post-counter">75<i class="fa fa-file-text"></i><span class="arrow">></span></div>
							<div class="clearfix"></div>
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
								<p class="subtext">Top hastags</p>
							</div>
							<span class="arrow">></span>
							<div class="clearfix"></div>
						</div>
						<div class="communities-row">
							<div class="com-content">
								<p class="zipcode">38605</p>
								<p class="subtext">Top hastags</p>
							</div>
							<span class="arrow">></span>
							<div class="clearfix"></div>
						</div>
						<div class="communities-row">
							<div class="com-content">
								<p class="zipcode">47241</p>
								<p class="subtext">Top hastags</p>
							</div>
							<span class="arrow">></span>
							<div class="clearfix"></div>
						</div>
					</div><!--end top communities content-->
				</div><!--end top communities-->
			</div>
			<div class="modal-footer">
				<div class="landing-btn btn-meet">Meet</div>
				<div class="landing-btn btn-my-community">My Community</div>
				<div class="landing-btn btn-explore">Explore</div>
			</div>
		</div>
	</div>
	<script id="city_name" type="text/x-underscore-template">
		<span class="title"><a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a><%= city %></span>
	</script>
</div>