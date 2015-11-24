<?php

use yii\db\Schema;

class m150910_020101_init_topic_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%topic}}', [
            'id' => Schema::TYPE_PK . '(11) NOT NULL',
            'city_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'post_count' => Schema::TYPE_INTEGER . '(11)',
            'view_count' => Schema::TYPE_INTEGER . '(11)',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME,
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');
    }

    public function down()
    {
        $this->dropTable('{{%topic}}');
    }
}
