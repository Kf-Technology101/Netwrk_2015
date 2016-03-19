<?php

use yii\db\Migration;
use yii\db\Schema;

class m160209_021000_user_group extends \yii\db\Migration
{
    public FUNCTION up()
    {
        $tableOptions = NULL;
        IF ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('user_group', [
            'id' => SCHEMA::TYPE_PK ,
            'user_id' => SCHEMA::TYPE_INTEGER . '(11) NOT NULL',
            'group_id' => SCHEMA::TYPE_INTEGER . '(11) NOT NULL'
        ], $tableOptions);
    }

    public FUNCTION down()
    {
        $this->dropTable('user_group');
    }
}