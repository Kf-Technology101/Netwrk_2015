<?php

use yii\db\Migration;
use yii\db\Schema;


class m151222_111924_add_column_post_type_post_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('post', 'post_type', Schema::TYPE_INTEGER. ' default 1');
    }

    public function down()
    {
        $this->dropColumn('post', 'post_type', Schema::TYPE_INTEGER);
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
