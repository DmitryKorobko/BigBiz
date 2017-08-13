<?php

use yii\db\Migration;

class m161123_080627_shop_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shop_category}}', [
            'id'           => $this->primaryKey(),
            'name'         => $this->string()->notNull(),
            'price'        => $this->double()->notNull(),
            'banner_price' => $this->integer()->notNull(),
            'priority'     => $this->integer()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%shop_category}}');
    }
}
