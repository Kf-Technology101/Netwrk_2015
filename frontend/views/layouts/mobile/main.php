  <?php
use frontend\assets\MobileAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

MobileAsset::register($this);
$controller = Yii::$app->controller;
$isCoverPage = 0;
if ( $controller->id == 'default' && $controller->action->id == 'index' ) {
    $isCoverPage = 1;
}
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
    var isMobile = true;
    var isCoverPage = <?php echo $isCoverPage; ?>;
    
  </script>
</head>
<body ontouchstart="">



  <div class="wrap-mobile" id="<?= ucfirst(Yii::$app->controller->id) ?>" data-action="<?= Yii::$app->controller->module->module->requestedAction->id ?>">
  
    <div id="myHeader" class="navbar-mobile navbar-fixed-top">
    	<div class="menu_top">
  			<div class="logo_netwrk option_logo_netwrk">
  				<a href="javascript:void(0)"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a>
  			</div>
        <div class="box-search">
          <div class="search input-group">
            <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control input-search" placeholder="What are your interests?">
          </div>
          <?= $this->render('@frontend/modules/netwrk/views/search/result') ?>
        </div>
    	</div>
      <?= $this->render('@frontend/modules/netwrk/views/user/userinfo') ?>
	  </div>
  
    <div class="container-fuild">
	    <?= Breadcrumbs::widget([
	      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	    ]) ?>
	    <?= $content ?>
    </div>
  
    <div id="myFooter" class="navbar-mobile navbar-fixed-bottom">
      <div class="menu_bottom">
        <div id="btn_meet_mobile"><img src="<?= Url::to('@web/img/icon/meet-icon-desktop.png'); ?>"></div>
        <!-- <div id="btn_discover_mobile"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"></div> -->
        <!-- <a href="javascript:void(0)" class='left'>Menu</a> -->
        <a class="right" id='chat_inbox_btn_mobile'><i class="fa fa-comment"></i><span class='notify disable'>0</span></a>
<!--         <div class="chatting">
            <span><i class="fa fa-comment"></i>Chat</span>
        </div> -->
      </div>
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
  if (isCoverPage) {
      document.getElementById('myHeader').classList.add("hidden");
      document.getElementById('myFooter').classList.add("hidden");
    }
</script>
</html>
<?php $this->endPage() ?>
