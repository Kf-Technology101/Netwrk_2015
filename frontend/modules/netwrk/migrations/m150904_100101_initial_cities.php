<?php

use yii\db\Schema;

class m150904_100101_initial_cities extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%city}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'lat' => Schema::TYPE_FLOAT,
            'lng' => Schema::TYPE_FLOAT,
        ], $tableOptions);
    }

    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS {{%city}}');
    }
}
