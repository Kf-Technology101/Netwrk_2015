<?php

use yii\db\Schema;

class m151120_040101_create_ws_messages extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('ws_messages', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'msg' => Schema::TYPE_TEXT,
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'msg_type' => Schema::TYPE_TEXT,
            'post_type'  => Schema::TYPE_INTEGER ,
            'created_at' => Schema::TYPE_STRING ,
            'updated_at' => Schema::TYPE_STRING ,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('ws_messages');
    }
}
