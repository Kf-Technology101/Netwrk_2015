<?php

use yii\db\Schema;

class m150923_030103_profile_example extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%profile}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'first_name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'last_name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'dob' => Schema::TYPE_DATETIME,
            'age' => Schema::TYPE_INTEGER,
            'work' => Schema::TYPE_STRING . '(45)',
            'photo' => Schema::TYPE_STRING . '(255)',
            'about' => Schema::TYPE_TEXT,
            'gender' => Schema::TYPE_STRING,
            'zip_code' => Schema::TYPE_INTEGER . '(4)',
            'lat' => Schema::TYPE_FLOAT,
            'lng' => Schema::TYPE_FLOAT,
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
    }
}
