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
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'first_name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'last_name' => Schema::TYPE_STRING . '(45) NOT NULL',
            'dob' => Schema::TYPE_DATETIME,
            'age' => Schema::TYPE_INTEGER,
            'work' => Schema::TYPE_STRING . '(45)',
            'photo' => Schema::TYPE_STRING . '(255)',
            'about' => Schema::TYPE_TEXT,
            'gender' => Schema::TYPE_STRING,
            'zip_code' => Schema::TYPE_INTEGER . '(4)',
            'lat' => Schema::TYPE_FLOAT,
            'lng' => Schema::TYPE_FLOAT,
        ], $tableOptions);

        $current_date = date('Y-m-d H:i:s');

        $this->execute("INSERT INTO {{%profile}} (user_id, first_name, last_name, dob, age , work, photo, about, gender, zip_code, lat, lng) VALUES
            (1,'AAAAA','AAAAA','2000-09-24 09:55:21',18,'Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (2,'BBBBB','BBBBB','1998-09-24 09:55:21',15,'Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (3,'CCCCC','CCCCC','1992-09-24 09:55:21',13,'Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (4,'DDDDD','DDDDD','2002-09-24 09:55:21',12,'Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286),
            (5,'EEEEE','AAAAA','1999-09-24 09:55:21',25,'Development',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',123, 39.13212, -86.523286);
        ");
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
    }
}
