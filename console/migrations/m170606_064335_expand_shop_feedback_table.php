<?php

use yii\db\Migration;

class m170606_064335_expand_shop_feedback_table extends Migration
{
    private $tableName = '{{%shop_feedback}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', "ENUM('UNREAD', 'READ') DEFAULT 'UNREAD'");
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }
}
