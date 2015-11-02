<?php

use yii\db\Schema;

class m151102_060101_add_column_view_count_to_post extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->addColumn('post', 'view_count', Schema::TYPE_FLOAT);
        $this->addColumn('post', 'brilliant_count', Schema::TYPE_FLOAT);
        $this->addColumn('post', 'comment_count', Schema::TYPE_FLOAT);
    }

    public function down()
    {
        $this->dropColumn('post', 'brilliant_count');
        $this->dropColumn('post', 'view_count');
        $this->dropColumn('post', 'comment_count');
    }
}
