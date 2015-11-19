<?php

use yii\db\Schema;

class m151118_070101_create_history extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('history', [
            'id' => Schema::TYPE_PK . '(11) NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('history');
    }
}
