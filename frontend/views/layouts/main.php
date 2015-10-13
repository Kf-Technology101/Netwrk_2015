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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap" id="<?= ucfirst(Yii::$app->controller->id) ?>" data-action="<?= Yii::$app->controller->module->module->requestedAction->id ?>">
        <?php
            NavBar::begin([
                'brandLabel' => 'Netwrk',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top menu_top',
                ],
            ]);
            $menuItems = [
                // ['label' => 'Home', 'url' => ['/site/index']],
                // ['label' => 'Menu', 'href' => ['/site/menu']],
                ['label' => 'Menu'],
                // ['label' => 'Talking', 'url' => ['/site/talking']],
                ['label' => 'Talking'],
                
            ];
            // if (Yii::$app->user->isGuest) {
            //     $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            // } else {
            //     $menuItems[] = [
            //         'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            //         'url' => ['/site/logout'],
            //         'linkOptions' => ['data-method' => 'post']
            //     ];
            // }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container-fluid map-padding">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
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
    var baseUrl = '<?php echo Url::base(true); ?>';
    var isMobile = false;
</script>
</html>
<?php $this->endPage() ?>
