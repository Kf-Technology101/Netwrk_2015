<?php
use frontend\assets\AppAsset;

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\Cookie;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$controller = Yii::$app->controller;
$cookies = Yii::$app->request->cookies;
$isCoverPage = 0;
$accepted = 0;
if (isset($cookies["isCoverPage"])) {
  $isCoverPage = $cookies->getValue('isCoverPage');//$cookies['isCoverPage']->value;
  $accepted = $cookies->getValue('accepted');
} else {
  $c = Yii::$app->response->cookies;
  $cookie = new Cookie(['name'=>'isCoverPage', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
  $c->add($cookie);
  $cookie = new Cookie(['name'=>'accepted', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
  $c->add($cookie);
  $isCoverPage = 1;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="public">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript">
    var baseUrl = '<?php echo Url::base(true); ?>';
    var isMobile = true;
    var isCoverPage = <?php echo $isCoverPage; ?>;
    var accepted = <?php echo $accepted; ?>;
  </script>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap" id="<?= ucfirst(Yii::$app->controller->id) ?>" data-action="<?= Yii::$app->controller->module->module->requestedAction->id ?>">
        <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top menu_top',
                ],
            ]);
        ?>
        <div class="logo_netwrk logo-glow">
            <span class="logo-active">
                <a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-icon-active.png'); ?>" alt="logo"/></a>
            </span>
            <span class="logo-inactive">
                <a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-icon-inactive.png'); ?>" alt="logo"/></a>
            </span>

        </div>
        <div class="box-search">
            <div class="search input-group">
                <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control input-search" placeholder="Be timeless">
            </div>
            <?= $this->render('@frontend/modules/netwrk/views/search/result') ?>
        </div>

        <div class="box-navigation text-right">
            <div id="nav_wrapper" class="navigation-btn-group btn-group btn-group-default btn-group-type" role="group" aria-label="...">
                <button id="btn_nav_map" type="button" class="btn btn-default btn_nav_map">
                    <i class="navigation-icon fa fa-globe"></i>
                    <span class="navigation-text">Map</span>
                </button>
                <button id="chat_inbox_btn" type="button" class="btn btn-default">
                    <i class="navigation-icon fa fa-comment"></i>
                    <span class="navigation-text"><span class='notify hide'>15</span>Chat</span>
                </button>
                <button id="btn_nav_meet" type="button" class="btn btn-default">
                    <i class="navigation-icon ci-meet"></i>
                    <span class="navigation-text">Meet</span>
                </button>
                <?php if (Yii::$app->user->isGuest):?>
                    <button type="button" class="btn btn-default login-trigger">
                        <i class="navigation-icon fa fa-sign-in"></i>
                        <div class="navigation-text">Login</div>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?= $this->render('@frontend/modules/netwrk/views/user/userinfo') ?>

        <?php
            // $menuItems = [
            //     ['label' => 'Menu'],
            //     ['label' => 'Talking'],
            // ];
            // if (Yii::$app->user->isGuest) {
            //     $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            // } else {
            //     $menuItems[] = [
            //         'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            //         'url' => ['/site/logout'],
            //         'linkOptions' => ['data-method' => 'post']
            //     ];
            // }
            // echo Nav::widget([
            //     'options' => ['class' => 'navbar-nav navbar-right'],
            //     'items' => $menuItems,
            // ]);
            NavBar::end();
        ?>

        <div class="container-fluid map-padding">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>
<script type="text/javascript">
    var baseUrl = '<?php echo Url::base(true); ?>',
    isMobile = false,
    isGuest = '<?php echo Yii::$app->user->isGuest ?>',
    isResetPassword ="<?= Yii::$app->session['key_reset_password'] ?>",
    isInvalidKey = "<?= Yii::$app->session['invalidKey'] ?>";
    var UserLogin = '<?php echo Yii::$app->user->id; ?>';
    // if (!isCoverPage) {
    //   document.getElementById('w0').classList.add("hidden");
    // }
</script>
<?php
    unset(Yii::$app->session['key_reset_password']);
    unset(Yii::$app->session['invalidKey']);
    Yii::$app->session->destroy();
?>
</html>
<?php $this->endPage() ?>
