<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_feedback`.
 */
class m170206_123057_create_product_feedback_table extends Migration
{
    private $tableName = '{{%product_feedback}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'product_id'   => $this->integer(11)->notNull(),
            'user_id'      => $this->integer(11)->notNull(),
            'text'         => $this->text()->notNull(),
            'rating'       => $this->integer(11)->notNull(),
            'created_at'   => $this->integer(),
            'updated_at'   => $this->integer()
        ]);

        $this->addForeignKey('fk-product_feedback-user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-product_feedback-product', $this->tableName, 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-product_feedback-user', $this->tableName);
        $this->dropForeignKey('fk-product_feedback-product', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
