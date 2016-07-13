<?php

use yii\db\Schema;
use yii\db\Migration;

class m160704_114313_feedback extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('feedback', [
            'id' => Schema::TYPE_PK,
            'feedback' => Schema::TYPE_STRING . '(56) NOT NULL',
            'point' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'ws_message_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'post_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'type' => Schema::TYPE_STRING . '(56) NOT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP. ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m160704_114313_feedback cannot be reverted.\n";

        return false;
    }
}
