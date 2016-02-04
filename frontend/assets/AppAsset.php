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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/desktop/landing.css',
        'css/font/font.css',
        'css/desktop/topic.css',
        'css/desktop/groups_loc.css',
        'css/desktop/meet.css',
        'css/desktop/post.css',
        'css/desktop/groups.css',
        'css/desktop/chat_post.css',
        'css/desktop/login.css',
        'css/desktop/signup.css',
        'css/jquery.ui.css',
        'css/jquery.ui.pips.css',
        'css/bootstrap-datepicker.min.css',
        'css/jquery.mCustomScrollbar.css',
        'css/emojione/css/emojione.min.css',
        'css/desktop/chat_inbox.css',
        'css/desktop/forgot_pass.css',
        'css/desktop/search.css',
        'css/desktop/popup_chat.css',
        'css/desktop/marker_popup.css',
        'css/desktop/dropdown_avatar.css',
        'css/desktop/landing_page.css'
    ];
    public $js = [
        'js/lib/underscore.js',
        'js/lib/label.js',
        'js/main.js',
        'js/vendor/bootbox.min.js',
        'js/controller/ajax.js',
        'js/controller/map.js',
        'js/controller/default.js',
        'js/controller/topic.js',
        'js/controller/create_topic.js',
        'js/controller/meet.js',
        'js/controller/template.js',
        'js/controller/profile.js',
        'js/controller/meet_setting.js',
        'js/controller/create_post.js',
        'js/controller/group.js',
        'js/controller/group_loc.js',
        'js/controller/create_group.js',
        //'js/controller/chat_post.js',
        'js/controller/post.js',
        'js/controller/vote.js',
        'js/controller/emoji.js',
        'js/controller/user.js',
        'js/controller/login.js',
        'js/controller/signup.js',
        'js/controller/search.js',
        // 'js/ws/ws.js',
        'js/vendor/reconnecting-websocket.js',
        'js/lib/jquery.ui.js',
        'js/lib/jquery.ui.pips.js',
        'js/lib/jquery.ui.touch_punch.js',
        'js/bootstrap-datepicker.min.js',
        'js/lib/jquery.mCustomScrollbar.concat.min.js',
        'js/lib/emojione.js',
        'js/controller/chat_inbox.js',
        'js/controller/forgot_password.js',
        'js/controller/reset_password.js',
        // 'js/controller/chat_private.js',
        'js/controller/popup_chat.js',
        'js/controller/main_ws.js',
        'js/controller/landing_page.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
