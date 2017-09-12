<?php

use yii\db\Migration;

class m170907_090103_edit_product_price extends Migration
{
    private $tableName = '{{%product_price}}';
    
    /** @inheritdoc */
    public function up()
    {
        $this->alterColumn($this->tableName, 'price', $this->double());
        $this->alterColumn($this->tableName, 'price_usd', $this->double());
    }

    /** @inheritdoc */
    public function down()
    {
        $this->alterColumn($this->tableName, 'price', $this->double()->notNull());
        $this->alterColumn($this->tableName, 'price_usd', $this->double()->notNull());
    }
}
