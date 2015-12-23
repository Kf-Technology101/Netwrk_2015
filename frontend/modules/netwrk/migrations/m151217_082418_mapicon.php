<?php

use yii\db\Migration;
use yii\db\Schema;

class m151217_082418_mapicon extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('city', 'office', Schema::TYPE_STRING.'(255)');
        $this->addColumn('city', 'office_type', Schema::TYPE_STRING.'(255)');
    }

    public function down()
    {
        $this->dropColumn('city', 'office');
        $this->dropColumn('city', 'office_type');
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
