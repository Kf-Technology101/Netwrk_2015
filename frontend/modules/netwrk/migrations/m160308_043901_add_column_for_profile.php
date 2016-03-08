<?php

use yii\db\Schema;

class m160308_043901_add_column_for_profile extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('profile', 'marital_status', Schema::TYPE_STRING . '(45)');
        $this->addColumn('profile', 'education', Schema::TYPE_STRING . '(100)');
        $this->addColumn('profile', 'country', Schema::TYPE_STRING . '(100)');
        $this->addColumn('profile', 'state', Schema::TYPE_STRING . '(100)');
        $this->addColumn('profile', 'city', Schema::TYPE_STRING . '(100)');
        $this->addColumn('profile', 'hobbies', Schema::TYPE_STRING . '(100)');
    }

    public function down()
    {
        $this->dropColumn('profile', 'marital_status', Schema::TYPE_STRING . '(45)');
        $this->dropColumn('profile', 'education', Schema::TYPE_STRING . '(100)');
        $this->dropColumn('profile', 'country', Schema::TYPE_STRING . '(100)');
        $this->dropColumn('profile', 'state', Schema::TYPE_STRING . '(100)');
        $this->dropColumn('profile', 'city', Schema::TYPE_STRING . '(100)');
        $this->dropColumn('profile', 'hobbies', Schema::TYPE_STRING . '(100)');
    }
}
