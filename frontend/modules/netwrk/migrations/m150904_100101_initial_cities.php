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

        $this->execute("INSERT INTO {{%city}} (name, lat, lng)
            VALUES ('Bloomington', 39.165325, -86.526386),
            ('Evansville', 37.971559, -87.571090),
            ('Fort Wayne', 41.079273, -85.139351),
            ('Indianapolis', 39.768403, -86.158068),
            ('Kokomo', 40.486427, -86.133603),
            ('West Lafayette', 40.425869, -86.908066),
            ('Muncie', 40.193377, -85.386360),
            ('Richmond', 39.828937, -84.890238),
            ('South Bend', 41.676355, -86.251990),
            ('Terre Haute', 39.466703, -87.413909);");
    }

    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS {{%city}}');
    }
}
