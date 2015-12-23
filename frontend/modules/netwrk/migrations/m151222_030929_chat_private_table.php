<?php

use yii\db\Migration;
use yii\db\Schema;

class m151222_030929_chat_private_table extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('chat_private', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'user_id_guest' => Schema::TYPE_INTEGER,
            'post_id' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_STRING,
            'updated_at' => Schema::TYPE_STRING,
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%chat_private}}');
        return false;
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
