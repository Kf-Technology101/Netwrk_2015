<?php

use yii\db\Schema;

class m150925_090101_user_meet extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user_meet}}', [
            'id' => Schema::TYPE_PK . '(11) NOT NULL',
            'user_id_1' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'user_id_2' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'status' => Schema::TYPE_BOOLEAN,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_meet}}');
    }
}
