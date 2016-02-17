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
      'css/mobile/groups.css',
      'css/mobile/meet.css',
      'css/mobile/setting.css',
      'css/mobile/post.css',
      'css/mobile/chat_post.css',
      'css/mobile/login.css',
      'css/mobile/signup.css',
      'css/bootstrap-datepicker.min.css',
      'css/jquery.ui.css',
      'css/jquery.ui.pips.css',
      'css/jquery.mCustomScrollbar.css',
      'css/emojione/css/emojione.min.css',
      'css/mobile/forgot_pass.css',
      'css/mobile/chat_inbox.css',
      'css/mobile/forgot_pass.css',
      'css/mobile/search.css',
      'css/mobile/dropdown_avatar.css',
      'css/mobile/landing_page.css',
      'css/mobile/cover_page.css'
    ];
    public $js = [
      'js/lib/underscore.js',
      'js/lib/label.js',
      'js/main.js',
      'js/vendor/bootbox.min.js',
      // 'js/ws/ws.js',
      // 'js/ws/chat.js',
      'js/controller/ajax.js',
      'js/controller/map.js',
      'js/controller/default.js',
      'js/controller/topic.js',
      'js/controller/group.js',
      'js/controller/create_topic.js',
      'js/controller/meet.js',
      'js/controller/profile.js',
      'js/controller/template.js',
      'js/controller/meet_setting.js',
      'js/controller/create_post.js',
      // 'js/controller/chat_post.js',
      'js/controller/post.js',
      'js/controller/vote.js',
      'js/controller/emoji.js',
      'js/controller/user.js',
      'js/controller/login.js',
      'js/controller/signup.js',
      'js/controller/search.js',
      'js/controller/common.js',
      'js/lib/jquery.ui.js',
      'js/lib/jquery.ui.pips.js',
      'js/lib/jquery.ui.touch_punch.js',
      'js/lib/jquery.mCustomScrollbar.concat.min.js',
      'js/bootstrap-datepicker.min.js',
      'js/lib/emojione.js',
      'js/controller/forgot_password.js',
      'js/controller/reset_password.js',
      'js/controller/chat_inbox.js',
      'js/vendor/reconnecting-websocket.js',
      'js/controller/main_ws.js',
      'js/controller/popup_chat.js',
      // 'js/controller/chat_private.js',
      'js/controller/landing_page.js',
      'js/controller/cover_page.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        // 'kartik\date\DatePickerAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
