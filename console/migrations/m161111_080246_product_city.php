<?php

use yii\db\Migration;

class m161111_080246_product_city extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product_city}}', [
            'id'            => $this->primaryKey(),
            'product_id'    => $this->integer()->notNull(),
            'city_id'       => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(        
            'fk-product_city-product',
            'product_city',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-product_city-city',
            'product_city',
            'city_id',
            'city',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-product_city-product',    '{{%product_city}}');
        $this->dropForeignKey('fk-product_city-city',       '{{%product_city}}');
        $this->dropTable('{{%product_city}}');
    }

}
