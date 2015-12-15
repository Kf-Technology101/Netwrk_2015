<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'frontend\components\UserComponent',
            'identityClass' => 'frontend\modules\netwrk\models\User',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // 'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['admin@rubyspace.net' => 'support@netwrk.com'], // this is needed for sending emails
                'charset' => 'UTF-8',
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'hungnhottest@gmail.com',
                'password' => 'Admintrum',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'authManager'  => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            // 'defaultRoles' => ['guest'],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'css' => ['css/bootstrap.css']
                ],
            ],
        ],
    ],
    // 'modules' => [
    //     'user' => [
    //         'class' => 'amnah\yii2\user\Module',
    //         'controllerMap' => [
    //             'default' => 'frontend\modules\netwrk\controllers\UserController',
    //         ],
    //         // set custom module properties here ...
    //     ],
    // ],
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
];
