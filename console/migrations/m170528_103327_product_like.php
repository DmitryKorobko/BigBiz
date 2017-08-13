<?php

use yii\db\Migration;

class m170528_103327_product_like extends Migration
{
    private $tableName = '{{%product_like}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'product_id' => $this->integer(11)->notNull(),
            'user_id'    => $this->integer(1)->notNull(),
            'like'       => $this->integer(1)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('fk-product_like-product', $this->tableName, 'product_id',
            'product', 'id', 'CASCADE');

        $this->addForeignKey('fk-product_like-user', $this->tableName, 'user_id',
            'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-product_like-product', $this->tableName);
        $this->dropForeignKey('fk-product_like-user', $this->tableName);
        $this->dropTable($this->tableName);
    }

}
