<?php

use yii\db\Migration;

class m161110_111739_product_price extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product_price}}', [
            'id'            => $this->primaryKey(),
            'product_id'    => $this->integer()->notNull(),
            'count'         => $this->string(255)->notNull(),
            'price'         => $this->double()->notNull()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-product_price-product',
            'product_price',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-product_price-product', '{{%product_price}}');
        $this->dropTable('{{%product_price}}');
    }

}
