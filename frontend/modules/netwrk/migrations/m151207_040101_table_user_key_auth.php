<?php

use yii\db\Schema;

class m151207_040101_table_user_key_auth extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_auth}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' not null',
            'provider' => Schema::TYPE_STRING . ' not null',
            'provider_id' => Schema::TYPE_STRING . ' not null',
            'provider_attributes' => Schema::TYPE_TEXT . ' not null',
            'create_time' => Schema::TYPE_TIMESTAMP . ' null default null',
            'update_time' => Schema::TYPE_TIMESTAMP . ' null default null'
        ], $tableOptions);

        $this->createTable('user_key', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'type' => Schema::TYPE_SMALLINT . '(6) NOT NULL',
            'key_value' => Schema::TYPE_STRING . '(255) NOT NULL',
            'create_time' => Schema::TYPE_TIMESTAMP,
            'consume_time' => Schema::TYPE_TIMESTAMP,
            'expire_time' => Schema::TYPE_TIMESTAMP,
        ], $tableOptions);

        $this->createIndex('{{%user_email}}', '{{%user}}', 'email', true);
        $this->createIndex('{{%user_username}}', '{{%user}}', 'username', true);
        $this->createIndex('{{%user_key_key}}', '{{%user_key}}', 'key_value', true);
        $this->createIndex('{{%user_auth_provider_id}}', '{{%user_auth}}', 'provider_id', false);

        // add foreign keys for data integrity
        $this->addForeignKey('{{%user_role_id}}', '{{%user}}', 'role_id', '{{%role}}', 'id');
        $this->addForeignKey('{{%profile_user_id}}', '{{%profile}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%user_key_user_id}}', '{{%user_key}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%user_auth_user_id}}', '{{%user_auth}}', 'user_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropTable('user_key');
        $this->dropTable('user_auth');
    }
}
