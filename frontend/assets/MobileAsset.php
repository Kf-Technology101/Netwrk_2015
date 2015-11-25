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
      'css/font/font.css',
      'css/mobile/landing.css',
      'css/mobile/topic.css',
      'css/mobile/meet.css',
      'css/mobile/setting.css',
      'css/mobile/post.css',
      'css/mobile/chat_post.css',
      'css/bootstrap-datepicker.min.css',
      'css/jquery.ui.css',
      'css/jquery.ui.pips.css',
      'css/jquery.mCustomScrollbar.css',
      'css/emojione/css/emojione.min.css'
    ];
    public $js = [
      'js/lib/underscore.js',
      'js/main.js',
      'js/ws/ws.js',
      // 'js/ws/chat.js',
      'js/bootstrap-datepicker.min.js',
      'js/controller/ajax.js',
      'js/controller/map.js',
      'js/controller/default.js',
      'js/controller/topic.js',
      'js/controller/create_topic.js',
      'js/controller/meet.js',
      'js/controller/profile.js',
      'js/controller/template.js',
      'js/controller/meet_setting.js',
      'js/controller/create_post.js',
      'js/controller/chat_post.js',
      'js/controller/post.js',
      'js/controller/vote.js',
      'js/controller/emoji.js',
      'js/lib/jquery.ui.js',
      'js/lib/jquery.ui.pips.js',
      'js/lib/jquery.ui.touch_punch.js',
      'js/lib/jquery.mCustomScrollbar.concat.min.js',
      'js/lib/emojione.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        // 'kartik\date\DatePickerAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
