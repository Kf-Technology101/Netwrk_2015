<?php

use yii\db\Schema;

class m160114_020101_table_hashtag extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('hashtag', [
            'id'  => Schema::TYPE_PK,
            'hashtag' =>Schema::TYPE_TEXT . ' NOT NULL',
            'count_total'=> Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('hashtag');
    }

}
