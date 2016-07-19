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
    <?php endif; ?>
  </script>
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
               data-placement="bottom" data-content="<?php echo $logo_popover; ?>">
            <span class="logo-active">
              <a href="javascript:void(0)"><img src="<?= Url::to('@web/img/icon/netwrk-icon-active.png'); ?>"></a>
            </span>
            <span class="logo-inactive">
              <a href="javascript:void(0)"><img src="<?= Url::to('@web/img/icon/netwrk-icon-inactive.png'); ?>"></a>
            </span>
          </div>
  			<!--<div class="logo_netwrk option_logo_netwrk">
  				<a href="javascript:void(0)"><img src="<?/*= Url::to('@web/img/icon/netwrk-logo-blue.png'); */?>"></a>
  			</div>-->
            <div class="box-search">
              <div class="search input-group">
                <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control input-search" placeholder="Be timeless">
              </div>
              <?= $this->render('@frontend/modules/netwrk/views/search/result') ?>
            </div>
            <div id="btn_my_location" class="btn_my_location">
              <i class="fa fa-plus"></i>
              <span>Build</span>
            </div>
    	</div>
	</div>
    <section class="navigation-wrapper text-center">
      <div class="box-navigation">
        <div id="nav_wrapper" class="navigation-btn-group btn-group btn-group-default btn-group-type" role="group" aria-label="...">
          <?php
            if (isset($cookies["nw_glow_near_btn"])) {
              $near_class = 'btn-nav-map';
            } else {
              $near_class = 'btn-nav-map glow-btn-wrapper';
            }

            if (isset($cookies["nw_popover_near"])) {
              $near_popover_class = '';
              $near_popover = '';
            } else {
              $near_popover_class = 'popover-near';
              $near_popover = 'Follow other areas and see what&rsquo;s around you';
            }
          ?>
          <div class="<?php echo $near_class;?> <?php echo $near_popover_class;?>"
               data-placement="top" data-content="<?php echo $near_popover; ?>">
            <button type="button" class="btn-explore-location btn-active">
              <i class="navigation-icon fa fa-globe"></i>
              <div class="navigation-text">Near</div>
            </button>
            <button type="button" class="btn-explore-location btn-inactive">
              <i class="navigation-icon fa fa-globe"></i>
              <div class="navigation-text">Near</div>
            </button>
          </div>
          <?php if (Yii::$app->user->isGuest):?>
            <a href="<?php echo Url::base(true); ?>/netwrk/user/login" type="button" class="btn btn-default">
              <i class="navigation-icon fa fa-sign-in"></i>
              <div class="navigation-text">Login</div>
            </a>
          <?php endif; ?>
          <button id="btn_nav_meet_mobile" type="button" class="btn btn-default">
            <i class="navigation-icon ci-meet"></i>
            <!--<div class="navigation-text">Meet</div>-->
          </button>
          <button id="chat_inbox_nav_btn_mobile" type="button" class="btn btn-default">
            <i class="navigation-icon ci-line"></i>
            <div class="navigation-text"><span class='notify disable'>0</span>Lines</div>
          </button>
        </div>
      </div>
    </section>
    <?php echo $this->render('@frontend/modules/netwrk/views/user/mobile/userinfo') ?>
  <?php endif; ?>
    <div class="container">
	    <?= Breadcrumbs::widget([
	      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	    ]) ?>
	    <?= $content ?>
    </div>

    <section class="btn-meet-mobile-wrapper text-center">
      <div id="btn_meet_mobile"><img src="<?= Url::to('@web/img/icon/meet-icon-desktop.png'); ?>"></div>
    </section>

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
