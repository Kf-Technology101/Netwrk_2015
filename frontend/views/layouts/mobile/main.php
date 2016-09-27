  <?php
use frontend\assets\MobileAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */

MobileAsset::register($this);
$controller = Yii::$app->controller;
$cookies = Yii::$app->request->cookies;
$isCoverPageVisited = 0;
$isAccepted = 0;
if (isset($cookies["isCoverPageVisited"])) {
  $isCoverPageVisited = $cookies->getValue('isCoverPageVisited');//$cookies['isCoverPage']->value;
  $isAccepted = $cookies->getValue('isAccepted');
  $city = $cookies->getValue('nw_city');
  $zipCode = $cookies->getValue('nw_zipCode');
  $lat = $cookies->getValue('nw_lat');
  $lng = $cookies->getValue('nw_lng');
  $state = $cookies->getValue('nw_state');
  $stateAbbr = $cookies->getValue('nw_stateAbbr');
  $welcomePage = $cookies->getValue('nw_welcomePage');
  $social_sign_up = $cookies->getValue('nw_social_sign_up');
  $userLocationInfo = $cookies["nw_userLocationInfo"];
}/* else {
  $c = Yii::$app->response->cookies;
  $cookie = new Cookie(['name'=>'isCoverPageVisited', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
  $c->add($cookie);
  $cookie = new Cookie(['name'=>'isAccepted', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
  $c->add($cookie);
  $isCoverPageVisited = 1;
}*/
// if ( $controller->id == 'default' && $controller->action->id == 'index' ) {
//     $isCoverPageVisited = 1;
// }
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>"/>
  <meta http-equiv="Cache-control" content="public">
  <meta name="msapplication-tap-highlight" content="no"/>
  <meta name="viewport" id="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>

  <script type="text/javascript">
    var baseUrl = '<?php echo Url::base(true); ?>';
    var ENV = '<?php echo YII_ENV; ?>';
    var isMobile = true;
    <?php if (isset($cookies["isCoverPageVisited"])) : ?>
      var isCoverPageVisited = <?php echo $isCoverPageVisited; ?>;
      var isAccepted = <?php echo $isAccepted; ?>;
      var city = '<?php echo $city; ?>';
      var zipCode = <?php echo $zipCode; ?>;
      var lat = <?php echo $lat; ?>;
      var lng = <?php echo $lng; ?>;
      var state = '<?php echo $state; ?>';
      var stateAbbr = '<?php echo $stateAbbr; ?>';
      var welcomePage = '<?php echo ($welcomePage) ? $welcomePage : "true"; ?>';
      var userLocationInfo = '<?php echo ($userLocationInfo) ? $userLocationInfo : "true"; ?>';
    <?php endif; ?>
    // If sign up with social platform, then display on boarding after sign up
    <?php
        if(isset($cookies['nw_social_sign_up']) && $social_sign_up == 1) {
        $c = Yii::$app->response->cookies;
        $cookie = new Cookie(['name'=>'nw_social_sign_up', 'value'=> 0]);
        $c->add($cookie);
    ?>
      sessionStorage.on_boarding = 1;
    <?php } ?>
  </script>
  <?php if (YII_ENV == 'prod'):?>
      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-81331474-1', 'auto');
      ga('send', 'pageview');

    </script>
  <?php endif; ?>
</head>
<body ontouchstart="">



  <div class="wrap-mobile" id="<?= ucfirst(Yii::$app->controller->id) ?>" data-action="<?= Yii::$app->controller->module->module->requestedAction->id ?>">

  <?php if (isset($cookies["isCoverPageVisited"])) : ?>
    <div id="myHeader" class="navbar-mobile navbar-fixed-top">
    	<div class="menu_top">
          <?php
            if (isset($cookies["nw_glow_logo"]))
              $logo_class = 'logo_netwrk option_logo_netwrk';
            else
              $logo_class = 'logo_netwrk option_logo_netwrk logo-glow';

            if (isset($cookies["nw_popover_logo"])) {
              $logo_popover_class = '';
              $logo_popover = '';
            } else {
              $logo_popover_class = 'popover-logo';
              $logo_popover = 'See your community news';
            }
          ?>
          <div class="<?php echo $logo_class;?> <?php echo $logo_popover_class;?>"
               data-template='<div class="popover info-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_logo" data-wrapper="popover-logo">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
               data-placement="bottom" data-content="<?php echo $logo_popover; ?>">
            <span class="logo-active">
              <a href="javascript:void(0)" class="landing-trigger"><img src="<?= Url::to('@web/img/icon/netwrk-icon-active.png'); ?>"></a>
            </span>
            <span class="logo-inactive">
              <a href="javascript:void(0)" class="landing-trigger"><img src="<?= Url::to('@web/img/icon/netwrk-icon-inactive.png'); ?>"></a>
            </span>
          </div>
  			<!--<div class="logo_netwrk option_logo_netwrk">
  				<a href="javascript:void(0)"><img src="<?/*= Url::to('@web/img/icon/netwrk-logo-blue.png'); */?>"></a>
  			</div>-->
            <div class="netwrk-title text-center"><img src="<?= Url::to('@web/img/icon/netwrk-text-mobile.png'); ?>" alt="Netwrk"/></div>
            <div class="search-trigger"><i class="fa fa-search"></i></div>
            <div class="box-search" id="mobileSearchBox">
              <div class="search input-group">
                <input type="text" class="form-control input-search" placeholder="Search Netwrk">
                <span class="input-group-addon close-search-trigger" id="sizing-addon2"><i class="fa fa-search"></i></span>
              </div>
              <?= $this->render('@frontend/modules/netwrk/views/search/result') ?>
            </div>
            <!--<div id="btn_my_location" class="btn_my_location">
              <i class="fa fa-plus"></i>
              <span>Build</span>
            </div>-->
          <!--<div class="btn-explore-location">
            <i class="fa fa-globe"></i>
            <span>Near</span>
          </div>-->
    	</div>
	</div>
    <section class="navigation-wrapper text-center">
      <div class="box-navigation">
        <div id="nav_wrapper" class="btn-group btn-group-default btn-group-type navbar-fixed-bottom" role="group">
          <button type="button" class="btn btn-default btn-nav-map btn-explore-location">
            <i class="navigation-icon fa fa-globe"></i>
            <div class="navigation-text">Map</div>
          </button>
          <button id="chat_inbox_nav_btn_mobile" type="button" class="btn btn-default">
            <i class="navigation-icon fa fa-comment"></i>
            <div class="navigation-text">Near</div>
          </button>
          <button id="btn_nav_meet_mobile" type="button" class="btn btn-default">
            <i class="navigation-icon ci-meet"></i>
            <div class="navigation-text">Meet</div>
          </button>
          <?php if (Yii::$app->user->isGuest):?>
            <a href="<?php echo Url::base(true); ?>/netwrk/user/login" type="button" class="btn btn-default">
              <i class="navigation-icon fa fa-sign-in ci-sign-in"></i>
              <div class="navigation-text">Login</div>
            </a>
          <?php else : ?>
            <button class="btn btn-default profile-trigger" type="button" id="buttonProfileWrapper">
              <i class="navigation-icon fa fa-user"></i>
              <div class="navigation-text">
                Profile
              </div>
            </button>
          <?php endif; ?>
        </div>
        <!--<div id="nav_wrapper" class="navigation-btn-group btn-group btn-group-default btn-group-type" role="group" aria-label="...">
          --><?php
/*            if (isset($cookies["nw_glow_near_btn"])) {
              $near_class = 'btn-nav-map';
            } else {
              $near_class = 'btn-nav-map glow-btn-wrapper';
            }

            $data_controller = Yii::$app->controller->id;
            $data_action = Yii::$app->controller->module->module->requestedAction->id;

            if (isset($cookies["nw_popover_near"])) {
              $near_popover_class = '';
              $near_popover = '';
            } elseif($data_action == 'landing-page' && $data_controller == 'default') {
              $near_popover_class = 'popover-near';
              $near_popover = 'Follow other areas and see what&rsquo;s around you';
            } else {
              $near_popover_class = '';
              $near_popover = '';
            }
          */?>
          <!--<div class="<?php /*echo $near_class;*/?> <?php /*echo $near_popover_class;*/?>"
               data-template='<div class="popover info-popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><span class="popover-close-trigger" data-cookie="nw_popover_near" data-wrapper="popover-near">&times;</span></div><div class="popover-title"></div><div class="popover-content"></div></div>'
               data-placement="top" data-content="<?php /*echo $near_popover; */?>">
            <button type="button" class="btn-explore-location btn-active">
              <i class="navigation-icon fa fa-globe"></i>
              <div class="navigation-text">Near</div>
            </button>
            <button type="button" class="btn-explore-location btn-inactive">
              <i class="navigation-icon fa fa-globe"></i>
              <div class="navigation-text">Near</div>
            </button>
          </div>
          <?php /*if (Yii::$app->user->isGuest):*/?>
            <a href="<?php /*echo Url::base(true); */?>/netwrk/user/login" type="button" class="btn btn-default">
              <i class="navigation-icon fa fa-sign-in ci-sign-in"></i>
              <div class="navigation-text">Login</div>
            </a>
          <?php /*endif; */?>
          <button id="btn_nav_meet_mobile" type="button" class="btn btn-default">
            <i class="navigation-icon ci-meet"></i>
          </button>
          <button id="chat_inbox_nav_btn_mobile" type="button" class="btn btn-default">
            <i class="navigation-icon ci-line"></i>
            <div class="navigation-text"><span class='notify disable'>0</span>Lines</div>
          </button>-->
        <!--</div>-->
      </div>
    </section>
    <?php echo $this->render('@frontend/modules/netwrk/views/user/mobile/userinfo') ?>
  <?php endif; ?>
    <div class="container">
	    <?= Breadcrumbs::widget([
	      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	    ]) ?>
	    <?= $content ?>
        <?= $this->render('@frontend/modules/netwrk/views/default/partial/area_news_slider');?>
    </div>

    <section class="btn-meet-mobile-wrapper text-center hide">
      <div id="btn_meet_mobile"><img src="<?= Url::to('@web/img/icon/meet-icon-desktop.png'); ?>"></div>
    </section>
    <div class="loader-text-wrap hide">
      <div class="netwrk-text-loader">
        <img src="<?= Url::to('@web/img/icon/loader-text.gif'); ?>" alt="loading..."/>
      </div>
    </div>

    <!--<div id="myFooter" class="navbar-mobile navbar-fixed-bottom hide">
      <div class="menu_bottom">

        <!-- <div id="btn_discover_mobile"><img src="<?/*= Url::to('@web/img/icon/meet_btn.png'); */?>"></div> -->
        <!-- <a href="javascript:void(0)" class='left'>Menu</a> -->
        <!--<a class="right" id='chat_inbox_btn_mobile'><i class="fa fa-comment"></i><span class='notify disable'>0</span></a>-->
        <!-- <div class="chatting">
            <span><i class="fa fa-comment"></i>Chat</span>
        </div> -->
     <!-- </div>-->
    </div>
  </div>
  
  <!-- <footer class="footer">
      <div class="container">
      <p class="pull-left">&copy; Netwrk <?= date('Y') ?></p>
      <p class="pull-right"><?php //Yii::powered() ?></p>
      </div>
  </footer> -->
  <?= $this->render('@frontend/modules/netwrk/views/default/partial/come_back_later');?>
  <?= $this->render('@frontend/modules/netwrk/views/default/partial/netwrk_navigation');?>
  <?= $this->render('@frontend/modules/netwrk/views/default/partial/netwrk_news');?>
  <?php $this->endBody() ?>
</body>
<script type="text/javascript">
  var isMobile = true;
  var isGuest = '<?php echo Yii::$app->user->isGuest; ?>';
  var UserLogin = '<?php echo Yii::$app->user->id; ?>';
  // if (isCoverPage) {
  //     document.getElementById('myHeader').classList.add("hidden");
  //     document.getElementById('myFooter').classList.add("hidden");
  // }
</script>
<!-- Code to open group topic page -->
<?php
  $url = $_SERVER['REQUEST_URI'];
  $parts = parse_url($url);
  $path = explode('/',$parts['path']);
  if($path[2] == 'topic' && $path[3] == 'topic-page') {
    parse_str($parts['query'], $query);
    $from = ($query['from']) ? $query['from'] : '';
    $group = ($query['group']) ? $query['group'] : 0;
    $groupName = ($query['name']) ? $query['name'] : '';

    if($from == 'profile' && $group > 0) {
?>
  <script type="application/javascript">
    Topic.tab_current = 'groups';

    window.onload = function() {
      // Load group topics and display the same
      Group.data.filter = 'recent';
      var group = <?php echo $group;?>;
      var groupName = '<?php echo $groupName;?>';
      var parent = $('#item_topic_group_list_' + Group.data.filter);

      Group.ShowTopics(parent, group, groupName);

      $('#modal_topic').find(".dropdown").removeClass('visible');
      $('#modal_topic .sidebar').find('.dropdown').addClass('visible');

      //Hide the group tab header from topic modal.
      Topic.HideTabGroupHeader();
    };
  </script>
<?php } } ?>
</html>
<?php $this->endPage() ?>
