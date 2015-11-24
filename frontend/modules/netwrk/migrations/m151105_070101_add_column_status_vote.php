<?php

use yii\db\Schema;

class m151105_070101_add_column_status_vote extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('vote', 'status', Schema::TYPE_BOOLEAN);
        $this->addColumn('vote', 'updated_at', Schema::TYPE_DATETIME);
    }

    public function down()
    {
        $this->dropColumn('vote', 'status', Schema::TYPE_BOOLEAN);
        // $this->dropColumn('vote', 'updated_at', Schema::TYPE_DATETIME);
    }
}
