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
            (1,'Jenny','Steve','1990-01-16 09:55:21',25,'Developer',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Female',47708, 39.13212, -86.523286),
            (2,'Victor','Lee','1989-02-15 09:55:21',26,'Tester',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',47725, 39.13212, -86.523286),
            (3,'Jason','Chen','1980-03-12 09:55:21',35,'Business Analyst',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',47619, 39.13212, -86.523286),
            (4,'Justin','Bieber','1979-04-01 09:55:21',36,'Singer',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',47660, 39.13212, -86.523286),
            (5,'Selena','Gomez','1965-05-20 09:55:21',50,'Doctor',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Female',47558, 39.13212, -86.523286),
            (6,'Ashley','Tisdale','1964-06-25 09:55:21',51,'Teacher',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Female',47453, 39.13212, -86.523286),
            (7,'Megan','Nicole','2000-07-30 09:55:21',15,'Student',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Female',46160, 39.13212, -86.523286),
            (8,'Jadin','Smith','1941-08-05 09:55:21',74,'Sales',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Male',46236, 39.13212, -86.523286),
            (9,'Ellie','Goulding','1939-09-23 09:55:21',76,'CEO',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Female',46910, 39.13212, -86.523286),
            (10,'Miley','Cyrus','1940-10-19 09:55:21',75,'Manager',null,'This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet','Female',46601, 39.13212, -86.523286);
        ");
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
    }
}
