<?php
use frontend\assets\AppAsset;

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
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
        <div class="logo_netwrk">
            <a href="<?= Url::base(true); ?>"><img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>"></a>
        </div>
        <div class="search input-group">
            <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control" placeholder="What are your interests?">
        </div>
        <div class="chatting" id='chat_inbox_btn'>
            <span><i class="fa fa-comment"></i>Chat</span>
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
</script>
<?php 
    unset(Yii::$app->session['key_reset_password']);
    unset(Yii::$app->session['invalidKey']);
    Yii::$app->session->destroy();
?>
</html>
<?php $this->endPage() ?>
