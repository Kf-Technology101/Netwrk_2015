<?php

use amnah\yii2\user\models\User;
use amnah\yii2\user\models\Role;
use yii\db\Schema;
use yii\db\Migration;

class m150214_044831_init_user extends Migration
{
    public function up()
    {
        $this->createTable('{{group}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->null(),
            'latitude' => $this->double(),
            'longitude' => $this->double(),
            'address' => "VARCHAR(256)",
            'name' => "VARCHAR(256)",
            'permission' => $this->integer(),
        ]);

        $this->addColumn('topic', 'group_id', $this->integer()->null());
        $this->addColumn('ws_messages', 'group_id', $this->integer()->null());
    }

    public function down()
    {
        $this->dropTable('group');
        $this->dropColumn('topic', 'group_id');
        $this->dropColumn('ws_messages', 'group_id');
    }
}