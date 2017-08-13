<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_product_favorite`.
 */
class m170109_074505_create_user_product_favorite_table extends Migration
{
    private $tableName = '{{%user_product_favorite}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'product_id' => $this->integer(11)->notNull(),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'updated_at' => $this->integer()->unsigned()->defaultValue(null)
        ]);

        $this->addForeignKey('fk-user_product_favorite-product', $this->tableName, 'product_id', 'product', 'id', 'CASCADE');
        $this->addForeignKey('fk-user_product_favorite-user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_product_favorite-product', $this->tableName);
        $this->dropForeignKey('fk-user_product_favorite-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
