<?php

use yii\db\Migration;
use yii\db\Schema;

class m151226_041617_notification_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => Schema::TYPE_PK,
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'sender' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'receiver' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'message' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'chat_show' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'created_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%notification}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
