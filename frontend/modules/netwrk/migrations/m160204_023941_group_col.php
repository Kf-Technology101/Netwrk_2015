<?php

use yii\db\Migration;
use yii\db\Schema;

class m160204_023941_group_col extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('group', [
            "id" => Schema::TYPE_PK,
            "user_id" => "int(11) NOT NULL",
            "city_id" => "int(11) DEFAULT NULL",
            "latitude" => "double NOT NULL",
            "longitude" => "double NOT NULL",
            "address" => "varchar(256) NOT NULL",
            "name" => "varchar(256) NOT NULL",
            "permission" => "int(11) NOT NULL",
            "created_at" => Schema::TYPE_TIMESTAMP,
            "updated_at" => Schema::TYPE_TIMESTAMP
        ], $tableOptions);

        $this->addColumn('topic', 'group_id', $this->integer());
        $this->addColumn('ws_messages', 'group_id', $this->integer());
    }

    public function down() {
        $this->dropTable("group");
        $this->dropColumn("topic", "group_id");
        $this->dropColumn("ws_messages", "group_id");
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
