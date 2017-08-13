<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_feedback`.
 */
class m170206_123940_create_shop_feedback_table extends Migration
{
    private $tableName = '{{%shop_feedback}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'                         => $this->primaryKey(),
            'shop_id'                    => $this->integer(11)->notNull(),
            'created_by'                 => $this->integer(11)->notNull(),
            'product_rating'             => $this->integer(11)->notNull(),
            'operator_rating'            => $this->integer(11)->notNull(),
            'reliability_rating'         => $this->integer(11)->notNull(),
            'marker_rating'              => $this->integer(11)->notNull(),
            'average_rating'             => $this->double()->notNull(),
            'created_at'                 => $this->integer(),
            'updated_at'                 => $this->integer()
        ]);

        $this->addForeignKey('fk-shop_feedback-user', $this->tableName, 'shop_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_feedback-usertable', $this->tableName, 'created_by', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-shop_feedback-user', $this->tableName);
        $this->dropForeignKey('fk-shop_feedback-usertable', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
