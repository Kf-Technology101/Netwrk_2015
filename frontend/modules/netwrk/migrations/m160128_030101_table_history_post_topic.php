<?php

use yii\db\Schema;

class m160128_030101_table_history_post_topic extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('history_feed', [
            'id' => Schema::TYPE_PK,
            'id_item'=> Schema::TYPE_INTEGER,
            'type_item'=> Schema::TYPE_STRING,
            'city_id'=> Schema::TYPE_INTEGER,
            'created_at'=> Schema::TYPE_DATETIME . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('history_feed');
    }
}
