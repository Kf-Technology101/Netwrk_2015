<?php

use yii\db\Schema;

class m151223_040101_add_brillant_count extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('city', 'brilliant_count', Schema::TYPE_INTEGER);
        $this->addColumn('topic', 'brilliant_count', Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->dropColumn('city', 'brilliant_count', Schema::TYPE_INTEGER);
        $this->dropColumn('topic', 'brilliant_count', Schema::TYPE_INTEGER);
    }
}
