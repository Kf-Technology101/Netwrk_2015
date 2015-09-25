<?php

use yii\db\Schema;

class m150923_020101_post_example extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%post}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'topic_id' => Schema::TYPE_INTEGER . '(11)',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'created_at' => Schema::TYPE_DATETIME ,
            'updated_at' => Schema::TYPE_DATETIME ,
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');
        $security = Yii::$app->security;

        $this->execute("INSERT INTO {{%post}} (title, content, topic_id, user_id, created_at, updated_at) VALUES
            ('Post Ex1','This blog Post Ex1',1,2,'{$current_date}','{$current_date}'),
            ('Post Ex2','This blog Post Ex2',1,2,'{$current_date}','{$current_date}'),
            ('Post Ex3','This blog Post Ex3',1,2,'{$current_date}','{$current_date}'),
            ('Post Ex4','This blog Post Ex4',1,2,'{$current_date}','{$current_date}'),
            ('Post Ex5','This blog Post Ex5',1,3,'{$current_date}','{$current_date}');
        ");
    }

    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
