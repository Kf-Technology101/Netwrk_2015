<?php

use yii\db\Schema;

class m150923_030101_vote_example extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%vote}}', [
            'id' => Schema::TYPE_PK . '(11) NOT NULL',
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'created_at' => Schema::TYPE_DATETIME,
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');
        $this->execute("INSERT INTO {{%vote}} (post_id, user_id, created_at) VALUES
                        (1,2,'{$current_date}'),
                        (1,3,'{$current_date}'),
                        (1,4,'{$current_date}'),
                        (1,5,'{$current_date}'),
                        (1,6,'{$current_date}'),
                        (1,7,'{$current_date}'),
                        (1,8,'{$current_date}'),
                        (1,9,'{$current_date}'),
                        (2,2,'{$current_date}'),
                        (2,3,'{$current_date}'),
                        (2,4,'{$current_date}'),
                        (3,5,'{$current_date}'),
                        (3,6,'{$current_date}');
                    ");
    }

    public function down()
    {
        $this->dropTable('{{%vote}}');
    }
}
