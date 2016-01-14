<?php

use yii\db\Schema;

class m160114_020101_table_post_hashtag extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('post_hashtag', [
            'id'  => Schema::TYPE_PK,
            'hashtag_id'=> Schema::TYPE_INTEGER,
            'post_id'=>Schema::TYPE_INTEGER
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('post_hashtag');
    }
}
