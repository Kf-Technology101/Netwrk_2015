<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        // 'admin' => [
        //     'class' => 'mdm\admin\Module',
        //     'layout' => 'right-menu',
        //     'mainLayout' => '@app/views/layouts/main.php',
        //     'menus' => [
        //         'assignment' => [
        //             'label' => 'Grant Access' // change label
        //         ],
        //         'route' => null, // disable menu
        //     ],
        // ],
        // 'gridview' => [
        //     'class' => '\kartik\grid\Module',
        // ],
        'netwrk' => [
            'class' => 'frontend\modules\netwrk\Module',
        ],
    ],
    'components' => [
        'user' => [
            'class' => 'frontend\components\UserComponent',
            'identityClass' => 'frontend\modules\netwrk\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                    'exportInterval' => 1
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // 'view' => [
        //     'theme' => [
        //         'pathMap' => [
        //            '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
        //         ],
        //     ],
        // ],
        'assetManager' => [
            'bundles' => [
                // 'dmstr\web\AdminLteAsset' => [
                //     'skin' => 'skin-purple',
                // ],
            ],
        ],
        // 'as access' => [
        //     'class' => 'mdm\admin\components\AccessControl',
        //     'allowActions' => [
        //         'site/*',
        //         'admin/*',
        //         // 'some-controller/some-action',
        //         // The actions listed here will be allowed to everyone including guests.
        //         // So, 'admin/*' should not appear here in the production, of course.
        //         // But in the earlier stages of your development, you may probably want to
        //         // add a lot of actions here until you finally completed setting up rbac,
        //         // otherwise you may not even take a first step.
        //     ]
        // ],
        'urlManager' => [
           'enablePrettyUrl' => true,
           'showScriptName' => false,
           'rules' => [
                '/' => 'netwrk/default/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                'request-password-reset' => 'site/request-password-reset',
                'reset-password' => 'site/reset-password',
            ]
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '616468941864933',
                    'clientSecret' => '17eaafc434a7d657a68890ba74cca4af',
                ],
            ],
        ]
    ],
    'params' => $params,
];
