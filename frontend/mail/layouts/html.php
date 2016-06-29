<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\mail\BaseMessage $content
 */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/CSS">
        body, #body_style {
            font-family:Arial, Helvetica, sans-serif;
            margin:0;
            padding:0;
            width:100% !important;
            line-height: 100% !important;
        }
        table {
            table-layout: fixed;
            margin:0 auto;
        }
        table table {
            table-layout: auto;
        }
    </style>
</head>
<body>
<span id="body_style" style="padding:15px; display:block">
    <table width="100%" align="center" style="font-family:Arial, Helvetica, sans-serif;width: 100%;padding: 20px;margin: 0 auto;" cellspacing="0" cellpadding="0" border="0">
        <?php $this->beginBody() ?>
            <?= $content ?>
        <?php $this->endBody() ?>
    </table>
</span>
</body>
</html>
<?php $this->endPage() ?>
