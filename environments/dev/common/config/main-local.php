<?php
/**
 * Local config for developer of environment.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

return [
    'language' => 'en',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=netwrk',
            'username' => 'root',
            'password' => 'isdvdsmysql1#$p',
            'charset' => 'utf8',
        ],
    ],
];
