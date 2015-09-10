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
            VALUES (1,1,'Topics Example 1',15, 20, '{$current_date}', '{$current_date}'),
            (1,1,'Topics Example 1',15, 20, '{$current_date}','{$current_date}'),
            (1,1,'Topics Example 1',150, 200,'{$current_date}','{$current_date}'),
            (1,1,'Topics Example 1.1',1500, 2000,'{$current_date}','{$current_date}'),
            (1,1,'Topics Example 1.2',1500, 2000,'{$current_date}','{$current_date}'),
            (1,1,'Topics Example 1.3',150, 200,'{$current_date}','{$current_date}'),
            (2,1,'Topics Example 1.4',150, 200,'{$current_date}','{$current_date}');");
    }

    public function down()
    {
        $this->dropTable('{{%topic}}');
    }
}
