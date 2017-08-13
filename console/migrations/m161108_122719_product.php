<?php

use yii\db\Migration;

class m161108_122719_product extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'description' => $this->text(),
            'image'       => $this->string()->notNull(),
            'availability'=> $this->integer()->notNull()->defaultValue(1),
            'user_id'     => $this->integer()->notNull(),
            'sort'        => $this->integer()->null(),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-product-user_id',
            'product',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

    }

    public function down()
    {

        $this->dropForeignKey('fk-product-shop_id', '{{%product}}');
        $this->dropTable('{{%product}}');
    }

}
