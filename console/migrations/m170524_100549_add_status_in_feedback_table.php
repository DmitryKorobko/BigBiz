<?php

use yii\db\Migration;

class m170524_100549_add_status_in_feedback_table extends Migration
{
    private $tableName = '{{%feedback}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', "ENUM('UNREAD', 'READ') DEFAULT 'UNREAD'");
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }
}
