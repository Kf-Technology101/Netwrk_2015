<?php

use yii\db\Schema;

class m150923_030102_user_example extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'role_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'status' => Schema::TYPE_SMALLINT . '(6) NOT NULL',
            'email' => Schema::TYPE_STRING . '(255)',
            'new_email' => Schema::TYPE_STRING . '(255)',
            'username' => Schema::TYPE_STRING . '(255)',
            'password' => Schema::TYPE_STRING . '(255)',
            'auth_key' => Schema::TYPE_STRING . '(255)',
            'api_key' => Schema::TYPE_STRING . '(255)',
            'login_ip' => Schema::TYPE_STRING . '(255)',
            'login_time' => Schema::TYPE_TIMESTAMP,
            'create_ip' => Schema::TYPE_STRING . '(255)',
            'create_time' => Schema::TYPE_TIMESTAMP,
            'update_time' => Schema::TYPE_TIMESTAMP,
            'ban_time' => Schema::TYPE_TIMESTAMP,
            'ban_reason' => Schema::TYPE_STRING . '(255)',
        ], $tableOptions);

        $security = Yii::$app->security;
        $this->execute("INSERT INTO {{%user}} (role_id, status, email, username, password, auth_key,api_key,create_time,update_time) VALUES
            ('1', '1', 'aaa@gmail.com','AAAAAAA', '{$security->generatePasswordHash('1qazxsw2')}', '{$security->generateRandomString()}', '{$security->generateRandomString()}','2015-09-23 08:33:46', '2015-09-23 08:33:46'),
            ('1', '1', 'bbb@gmail.com','BBBBBBB', '{$security->generatePasswordHash('1qazxsw2')}', '{$security->generateRandomString()}', '{$security->generateRandomString()}', '2015-09-23 08:33:46', '2015-09-23 08:33:46'),
            ('1', '1', 'ccc@gmail.com','CCCCCCC', '{$security->generatePasswordHash('1qazxsw2')}', '{$security->generateRandomString()}', '{$security->generateRandomString()}', '2015-09-23 08:33:46', '2015-09-23 08:33:46'),
            ('1', '1', 'ddd@gmail.com','DDDDDDD', '{$security->generatePasswordHash('1qazxsw2')}', '{$security->generateRandomString()}', '{$security->generateRandomString()}','2015-09-23 08:33:46', '2015-09-23 08:33:46'),
            ('1', '1', 'eee@gmail.com','EEEEEEE', '{$security->generatePasswordHash('1qazxsw2')}', '{$security->generateRandomString()}', '{$security->generateRandomString()}','2015-09-23 08:33:46', '2015-09-23 08:33:46');
        ");
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
