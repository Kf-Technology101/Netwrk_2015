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
        $this->execute("INSERT INTO {{%topic}} (city_id, user_id, title,post_count,view_count,created_at,updated_at)
            VALUES (1,1,'Topics Example 1',15, 20000, '2015-1-31 15:30:00', '{$current_date}'),
            (1,1,'Topics Example 1',150, 2000, '2014-1-31 1:30:00','{$current_date}'),
            (1,1,'Topics Example 1',1500, 200,'2015-1-31 15:30:00','{$current_date}'),
            (1,1,'Topics Example 1.1',15000, 20,'2013-1-31 2:30:00','{$current_date}'),
            (1,1,'Topics Example 1.2',150000, 2,'2007-1-31 15:30:00','{$current_date}'),
            (1,1,'Topics Example 1.3',1500000, 1,'2008-1-31 15:30:00','{$current_date}'),
            (2,1,'Topics Example 1.4',15000000, 1,'2012-1-31 21:30:00','{$current_date}'),
            (2,2,'Topics Example 1.4',1, 0,'2012-1-31 21:30:00','{$current_date}');");
    }

    public function down()
    {
        $this->dropTable('{{%topic}}');
    }
}
