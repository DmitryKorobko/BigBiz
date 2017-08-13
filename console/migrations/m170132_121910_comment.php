<?php

use yii\db\Migration;

class m170132_121910_comment extends Migration
{
    private $tableName = '{{%comment}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'text'         => $this->text()->notNull(),
            'theme_id'     => $this->integer()->notNull(),
            'created_by'   => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->null(),
            'status'       => "ENUM('READ', 'UNREAD') DEFAULT 'UNREAD'",
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-comment-theme', $this->tableName, 'theme_id', 'theme', 'id', 'CASCADE');
        $this->addForeignKey('fk-comment-user-created_by', $this->tableName, 'created_by', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-comment-user-recipient_id', $this->tableName, 'recipient_id', 'user', 'id', 'CASCADE');
        $this->createIndex('idx_created_by', $this->tableName, ['created_by']);
        $this->createIndex('idx_created_at', $this->tableName, ['created_at']);
    }

    public function down()
    {
        $this->dropForeignKey('fk-comment-theme', $this->tableName);
        $this->dropForeignKey('fk-comment-user-created_by', $this->tableName);
        $this->dropForeignKey('fk-comment-user-recipient_id', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
