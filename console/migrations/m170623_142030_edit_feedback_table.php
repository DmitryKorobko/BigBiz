<?php

use yii\db\Migration;

class m170623_142030_edit_feedback_table extends Migration
{
    private $tableName = '{{%feedback}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'cause_send', "ENUM('APPLICATION_PROBLEM', 'FUNCTIONAL_PROBLEM', 'WISHES')NOT NULL");
        $this->alterColumn($this->tableName, 'name', $this->string(255));
    }

    public function safeDown()
    {
        $this->dropColumn($this->table, 'cause_send');
        $this->alterColumn($this->table, 'name', $this->string(255)->notNull());
    }
}
