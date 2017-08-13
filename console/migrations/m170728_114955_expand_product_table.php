<?php

use yii\db\Migration;

class m170728_114955_expand_product_table extends Migration

{
    private $tableName = '{{%product}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'address', $this->string()->null());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'address');
    }
}
