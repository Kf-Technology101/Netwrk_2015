<?php

use yii\db\Schema;

class m151019_040101_add_column_zipcode extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        

        $this->addColumn('city', 'zip_code', Schema::TYPE_INTEGER);
        $this->addColumn('city', 'post_count', Schema::TYPE_INTEGER);
        $this->addColumn('city', 'user_count', Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->dropColumn('city', 'zip_code');
        $this->dropColumn('city', 'post_count');
        $this->dropColumn('city', 'user_count');
    }
}
