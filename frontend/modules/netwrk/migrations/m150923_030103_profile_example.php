<?php

use yii\db\Schema;

class m150923_030103_profile_example extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%profile}}', [
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'first_name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'last_name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'dob' => Schema::TYPE_DATETIME,
            'work' => Schema::TYPE_STRING . '(45)',
            'photo' => Schema::TYPE_STRING . '(255)',
            'about' => Schema::TYPE_TEXT,
            'gender' => Schema::TYPE_STRING,
            'zip_code' => Schema::TYPE_SMALLINT . '(4)',
            'lat' => Schema::TYPE_FLOAT,
            'lng' => Schema::TYPE_FLOAT,
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');

        $this->execute("INSERT INTO {{%profile}} (user_id, first_name, last_name, dob, work, photo, about, gender, zip_code, lat, lng) VALUES
            (1,'AAAAA','AAAAA','{$current_date}','Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (2,'BBBBB','BBBBB','{$current_date}','Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (3,'CCCCC','CCCCC','{$current_date}','Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (4,'DDDDD','DDDDD','{$current_date}','Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (5,'EEEEE','AAAAA','{$current_date}','Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286);
        ");
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
    }
}
