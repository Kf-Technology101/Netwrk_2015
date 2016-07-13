<?php

use yii\db\Schema;
use yii\db\Migration;

class m160704_062728_feedback_stat extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('feedback_stat', [
            'id' => Schema::TYPE_PK,
            'points' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'ws_message_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'post_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'type' => Schema::TYPE_STRING . '(56) NOT NULL'
        ], $tableOptions);
    }

    public function down()
    {
        echo "m160704_062728_feedback_stat cannot be reverted.\n";

        return false;
    }
}
