<?php

use yii\db\Schema;

class m151104_080101_add_pk_id_col_vote_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('vote', 'id', Schema::TYPE_PK . '(11) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('vote', 'id', Schema::TYPE_PK);
    }
}
