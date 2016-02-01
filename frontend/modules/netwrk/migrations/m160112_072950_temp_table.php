<?php

use yii\db\Migration;
use yii\db\Schema;

class m160112_072950_temp_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%temp}}', [
            'id' => Schema::TYPE_PK,
            'zipcode' => Schema::TYPE_INTEGER,
            'type' => Schema::TYPE_STRING,
            'city' => Schema::TYPE_STRING,
            'lat' => Schema::TYPE_FLOAT,
            'lng' => Schema::TYPE_FLOAT,
            'lat_min' => Schema::TYPE_FLOAT,
            'lat_max' => Schema::TYPE_FLOAT,
            'lng_min' => Schema::TYPE_FLOAT,
            'lng_max' => Schema::TYPE_FLOAT
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%temp}}');
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
