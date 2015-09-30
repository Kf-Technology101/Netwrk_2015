<?php

use yii\db\Schema;

class m150928_070101_user_settings extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user_settings}}', [
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'distance' => Schema::TYPE_FLOAT,
            'age' => Schema::TYPE_SMALLINT . '(4)',
            'gender' => Schema::TYPE_STRING,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_settings}}');
    }
}
