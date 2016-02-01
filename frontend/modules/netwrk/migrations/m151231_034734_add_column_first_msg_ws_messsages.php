<?php

use yii\db\Migration;
use yii\db\Schema;

class m151231_034734_add_column_first_msg_ws_messsages extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('ws_messages', 'first_msg', Schema::TYPE_BOOLEAN. ' DEFAULT 1');
    }

    public function down()
    {
        $this->dropColumn('ws_messages', 'first_msg', Schema::TYPE_BOOLEAN);
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
