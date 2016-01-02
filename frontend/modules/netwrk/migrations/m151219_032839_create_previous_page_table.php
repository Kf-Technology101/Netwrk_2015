<?php

use yii\db\Migration;
use yii\db\Schema;

class m151219_032839_create_previous_page_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('previous_page', [
            'id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_STRING,
            'updated_at' => Schema::TYPE_STRING,
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%previous_page}}');
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
