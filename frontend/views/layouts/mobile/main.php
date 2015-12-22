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
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>"/>
  <meta http-equiv="Cache-control" content="public">
  <meta name="viewport" id="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
  <script type="text/javascript">
    var baseUrl = '<?php echo Url::base(true); ?>';
    var isMobile = true;
  </script>
</head>
<body>



  <div class="wrap-mobile" id="<?= ucfirst(Yii::$app->controller->id) ?>" data-action="<?= Yii::$app->controller->module->module->requestedAction->id ?>">
    <div class="navbar-mobile navbar-fixed-top">
    	<div class="menu_top">
  			<div class="logo_netwrk">
  				<a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a>
  			</div>
        <div class="box-search">
          <div class="search input-group">
            <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control" placeholder="What are your interests?">
          </div>
          <!-- <?= $this->render('@frontend/modules/netwrk/views/search/result') ?> -->
        </div>
    	</div>
	</div>
    <div class="container-fuild">
	    <?= Breadcrumbs::widget([
	      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	    ]) ?>
	    <?= $content ?>
    </div>
    <div class="navbar-mobile navbar-fixed-bottom">
      <div class="menu_bottom">
        <div id="btn_meet_mobile"><img src="<?= Url::to('@web/img/icon/meet-icon.png'); ?>"></div>
        <!-- <div id="btn_discover_mobile"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"></div> -->
        <!-- <a href="javascript:void(0)" class='left'>Menu</a> -->
        <a class="right" id='chat_inbox_btn_mobile'><i class="fa fa-comment"></i></a>
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
</script>
</html>
<?php $this->endPage() ?>
