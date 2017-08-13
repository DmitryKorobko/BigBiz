<?php

use yii\db\Migration;

class m170705_063046_expand_shop_profile_table extends Migration
{
    private $tableName = '{{%shop_profile}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'created_at', $this->integer()->unsigned()->defaultValue(null));
        $this->addColumn($this->tableName, 'updated_at', $this->integer()->unsigned()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'created_at');
        $this->dropColumn($this->tableName, 'updated_at');
    }
}
