<?php

use yii\db\Schema;

class m150910_020101_init_topic_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%topic}}', [
            'id' => Schema::TYPE_PK . '(11) NOT NULL',
            'city_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'post_count' => Schema::TYPE_INTEGER . '(11)',
            'view_count' => Schema::TYPE_INTEGER . '(11)',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME,
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');
        $this->execute("INSERT INTO {{%topic}} (city_id, user_id, title,post_count,view_count,created_at,updated_at)
            VALUES 
            (2,1,'Topic one',15, 30, '2015-8-30 10:30:00', '2015-9-18 10:10:10'),
            (2,1,'Topic two',12, 25, '2015-7-15 18:30:00', '2015-8-18 15:20:10'),
            (2,1,'Topic three',4, 63, '2015-6-20 17:30:00', '2015-6-20 12:30:10'),
            (2,1,'Topic four',8, 12, '2015-9-18 12:35:26', '2015-5-8 16:11:10'),
            (2,1,'Topic five',1, 2, '2015-9-17 16:30:00', '2015-4-4 12:15:10'),
            (3,1,'Topic one',15, 30, '2015-8-30 10:30:00', '2015-9-18 10:10:10'),
            (3,1,'Topic two',12, 25, '2015-7-15 18:30:00', '2015-8-18 15:20:10'),
            (3,1,'Topic three',4, 63, '2015-6-20 17:30:00', '2015-6-20 12:30:10'),
            (3,1,'Topic four',8, 12, '2015-9-18 12:35:26', '2015-5-8 16:11:10'),
            (3,1,'Topic five',1, 2, '2015-9-17 16:30:00', '2015-9-18 17:15:10'),
            (4,1,'Topic one',15, 30, '2015-8-30 10:30:00', '2015-9-18 10:10:10'),
            (4,1,'Topic two',12, 25, '2015-7-15 18:30:00', '2015-8-18 15:20:10'),
            (4,1,'Topic three',4, 63, '2015-6-20 17:30:00', '2015-6-20 12:30:10'),
            (4,1,'Topic four',8, 12, '2015-9-18 12:35:26', '2015-5-8 16:11:10'),
            (4,1,'Topic five',1, 2, '2015-9-17 16:30:00', '2015-9-18 17:15:10'),
            (5,1,'Topic one',15, 30, '2015-8-30 10:30:00', '2015-9-18 10:10:10'),
            (5,1,'Topic two',12, 25, '2015-7-15 18:30:00', '2015-8-18 15:20:10'),
            (5,1,'Topic three',4, 63, '2015-6-20 17:30:00', '2015-6-20 12:30:10'),
            (5,1,'Topic four',8, 12, '2015-9-18 12:35:26', '2015-5-8 16:11:10'),
            (5,1,'Topic five',1, 2, '2015-9-17 16:30:00', '2015-9-18 17:15:10'),
            (6,1,'Topic one',15, 30, '2015-8-30 10:30:00', '2015-9-18 10:10:10'),
            (6,1,'Topic two',12, 25, '2015-7-15 18:30:00', '2015-8-18 15:20:10');");
    }

    public function down()
    {
        $this->dropTable('{{%topic}}');
    }
}
