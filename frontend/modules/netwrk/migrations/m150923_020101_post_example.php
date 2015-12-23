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
            'updated_at' => Schema::TYPE_DATETIME .' null default null',
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');
        $security = Yii::$app->security;
    }

    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
