<?php

use yii\db\Schema;

class m150923_030101_role extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('role', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(11) NOT NULL',
            'create_time' => Schema::TYPE_TIMESTAMP,
            'update_time' => Schema::TYPE_TIMESTAMP .' null default null',
            'can_admin' => Schema::TYPE_SMALLINT . '(6) NOT NULL'
        ], $tableOptions);
        
        $security = Yii::$app->security;
        $this->execute("INSERT INTO `role` (name, can_admin) VALUES ('Admin', 1), ('User', 0);");
    }

    public function down()
    {
        $this->dropTable('role');
    }
}
