<?php

use yii\db\Migration;
use yii\db\Schema;


class m160112_103400_add_column_post_chat_update_time extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('post', 'chat_updated_time', Schema::TYPE_DATETIME. ' default null');
    }

    public function down()
    {
        $this->dropColumn('post', 'chat_updated_time', Schema::TYPE_DATETIME);
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
