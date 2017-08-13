<?php

use yii\db\Migration;

class m170518_100910_add_field_status_online_to_user_table extends Migration
{
    private $tableName = '{{%user}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status_online', $this->integer(1)->defaultValue(1));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status_online');
    }
}