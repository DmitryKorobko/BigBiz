<?php

use yii\db\Migration;

class m170428_090412_edit_user_profile_table extends Migration
{
    private $tableName = '{{%user_profile}}';

    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'show_dob');
        $this->dropColumn($this->tableName, 'show_status_online');
        $this->dropColumn($this->tableName, 'push_notification');
    }

    public function safeDown()
    {
        $this->addColumn($this->tableName, 'show_dob', $this->integer(1)->defaultValue(1));
        $this->addColumn($this->tableName, 'show_status_online', $this->integer(1)->defaultValue(1));
        $this->addColumn($this->tableName, 'push_notification', $this->integer(1)->defaultValue(1));
    }
}
