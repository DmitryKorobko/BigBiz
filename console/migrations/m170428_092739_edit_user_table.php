<?php

use yii\db\Migration;

class m170428_092739_edit_user_table extends Migration
{
    private $tableName = '{{%user}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'is_deleted', $this->integer(1)->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'is_deleted');
    }
}
