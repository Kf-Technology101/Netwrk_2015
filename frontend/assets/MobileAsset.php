<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MobileAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'css/site.css',
      'css/mobile/landing.css',
      'css/mobile/topic.css',
      'css/mobile/meet.css',
      'css/mobile/setting.css',
    ];
    public $js = [
      'js/lib/underscore.js',
      'js/main.js',
      'js/ajax/get.js',
      'js/controller/default.js',
      'js/controller/topic.js',
      'js/controller/meet.js',
      'js/controller/profile.js',
      'js/controller/template.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
