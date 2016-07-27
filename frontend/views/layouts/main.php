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
} /*else {
  $c = Yii::$app->response->cookies;
  $cookie = new Cookie(['name'=>'isCoverPageVisited', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
  $c->add($cookie);
  $cookie = new Cookie(['name'=>'isAccepted', 'value'=> 1, 'expire'=> (time()+(365*86400))]);
  $c->add($cookie);
  $isCoverPageVisited = 1;
}*/
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
        var isMobile = false;
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
        <?php if (isset($cookies["nw_glow_logo"])) {?>
            var isLogoGlow = false;
        <?php } else { ?>
            var isLogoGlow = true;
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
<body>

    <?php $this->beginBody() ?>
    <!-- Loader -->
    <div class="loader-wrap hide">
        <div class="netwrk-loader">
            <img src="<?= Url::to('@web/img/icon/loader.gif'); ?>" alt="loading..."/>
        </div>
    </div>
    <!-- /Loader -->
    <div class="wrap" id="<?= ucfirst(Yii::$app->controller->id) ?>" data-action="<?= Yii::$app->controller->module->module->requestedAction->id ?>">
        <?php
            if (isset($cookies["isCoverPageVisited"])) :
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top menu_top',
                ],
            ]);
        ?>
        <?php
            if (isset($cookies["nw_glow_logo"])) {
                $logo_class = 'logo_netwrk';
            }
            else {
                $logo_class = 'logo_netwrk logo-glow';
            }

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
                <a href="javascript:" class="landing-trigger"><img src="<?= Url::to('@web/img/icon/netwrk-icon-active.png'); ?>" alt="logo"/></a>
            </span>
            <span class="logo-inactive">
                <a href="javascript:" class="landing-trigger"><img src="<?= Url::to('@web/img/icon/netwrk-icon-inactive.png'); ?>" alt="logo"/></a>
            </span>

        </div>
        <div class="box-search">
            <div class="search input-group">
                <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control input-search" placeholder="Be timeless">
            </div>
            <?= $this->render('@frontend/modules/netwrk/views/search/result') ?>
        </div>

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
            endif;
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
    ENV = '<?php echo YII_ENV; ?>',
    isMobile = false,
    isGuest = '<?php echo Yii::$app->user->isGuest ?>',
    isResetPassword ="<?= Yii::$app->session['key_reset_password'] ?>",
    isInvalidKey = "<?= Yii::$app->session['invalidKey'] ?>",
    isUserInvitation ="<?= Yii::$app->session['key_user_invitation'] ?>";
    var UserLogin = '<?php echo Yii::$app->user->id; ?>';
    // if (!isCoverPage) {
    //   document.getElementById('w0').classList.add("hidden");
    // }
</script>
<?php
    unset(Yii::$app->session['key_reset_password']);
    unset(Yii::$app->session['key_user_invitation']);
    unset(Yii::$app->session['invalidKey']);
    Yii::$app->session->destroy();
?>
</html>
<?php $this->endPage() ?>
