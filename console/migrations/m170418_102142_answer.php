<?php

use yii\db\Migration;

class m170418_102142_answer extends Migration
{
    private $tableName = '{{%answer}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'              => $this->primaryKey(),
            'type'            => "ENUM(
                'LIKE_THEME', 'LIKE_COMMENT', 'REPLY_COMMENT', 'NEW_PRODUCT_REPORT', 
                'NEW_THEME_COMMENT', 'NEW_PRODUCT_COMMENT', 'NEW_USER_REPUTATION', 'NEW_SHOP_REVIEW'
            )",
            'recipient_id'    => $this->integer(11)->notNull(),
            'created_by'      => $this->integer(11)->notNull(),
            'product_id'      => $this->integer(11),
            'theme_id'        => $this->integer(11),
            'comment_id'      => $this->integer(11),
            'text'            => $this->text()->notNull(),
            'status'          => "ENUM('UNREAD', 'READ') DEFAULT 'UNREAD'",
            'created_at'      => $this->integer()->notNull(),
            'updated_at'      => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-answer_user_recipient_id', $this->tableName, 'recipient_id','user',
            'id', 'CASCADE'
        );
        $this->addForeignKey(
            'fk-answer_user_created_by', $this->tableName, 'created_by','user',
            'id', 'CASCADE'
        );
        $this->addForeignKey(
            'fk-answer_product', $this->tableName, 'product_id','product',
            'id', 'CASCADE'
        );
        $this->addForeignKey(
            'fk-answer_theme', $this->tableName, 'theme_id','theme',
            'id', 'CASCADE'
        );
        $this->addForeignKey(
            'fk-answer_comment', $this->tableName, 'comment_id','comment',
            'id', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-answer_user_recipient_id', $this->tableName);
        $this->dropForeignKey('fk-answer_user_created_by', $this->tableName);
        $this->dropForeignKey('fk-answer_product', $this->tableName);
        $this->dropForeignKey('fk-answer_theme', $this->tableName);
        $this->dropForeignKey('fk-answer_comment', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
