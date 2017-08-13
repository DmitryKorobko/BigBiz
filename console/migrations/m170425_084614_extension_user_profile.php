<?php

use yii\db\Migration;

class m170425_084614_extension_user_profile extends Migration
{
    private $tableName = '{{%user_profile}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'reputation', $this->integer(11));
        $this->alterColumn($this->tableName, 'gender', "ENUM('MALE', 'FEMALE')");
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'reputation');
        $this->alterColumn($this->tableName, 'gender', $this->string(255)->null());
    }
}
