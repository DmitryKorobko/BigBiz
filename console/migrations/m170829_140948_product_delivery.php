<?php

use yii\db\Migration;

class m170829_140948_product_delivery extends Migration
{

    private $tableName = '{{%product_delivery}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'product_id' => $this->integer(11)->notNull(),
            'address'    => $this->string(255)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('fk-product_delivery-product', $this->tableName, 'product_id',
            'product', 'id', 'CASCADE');
        $this->dropColumn('product', 'address');
    }

    public function down()
    {
        $this->addColumn('product', $this->string(255));
        $this->dropForeignKey('fk-product_delivery-product', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
