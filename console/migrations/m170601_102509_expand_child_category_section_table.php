<?php

use yii\db\Migration;

class m170601_102509_expand_child_category_section_table extends Migration
{
    private $tableName = '{{%child_category_section}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'description', $this->string(255)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'description');
    }
}
