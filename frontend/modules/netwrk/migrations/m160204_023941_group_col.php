<?php

use yii\db\Migration;

class m160204_023941_group_col extends Migration {

    public function up() {
        $this->createTable('group', [
            "id" => "int(11) NOT NULL AUTO_INCREMENT",
            "user_id" => "int(11) NOT NULL",
            "city_id" => "int(11) DEFAULT NULL",
            "latitude" => "double NOT NULL",
            "longitude" => "double NOT NULL",
            "address" => "varchar(256) NOT NULL",
            "name" => "varchar(256) NOT NULL",
            "permission" => "int(11) NOT NULL",
            "created_at" => "datetime NOT NULL",
            "updated_at" => "datetime NOT NULL"
        ]);

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
