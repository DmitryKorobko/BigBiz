<?php

use yii\db\Migration;

/**
 * Handles the creation of table `message`.
 */
class m170216_135622_create_message_table extends Migration
{
    private $tableName = '{{%message}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'created_by'   => $this->integer(11)->notNull(),
            'recipient_id' => $this->integer(11)->notNull(),
            'text'         => $this->text()->notNull(),
            'image'        => $this->text()->null(),
            'status'       => "ENUM('READ', 'UNREAD') DEFAULT 'UNREAD'",
            'created_at'   => $this->integer(),
            'updated_at'   => $this->integer()
        ]);

        $this->addForeignKey('fk-message-user-created_by', $this->tableName, 'created_by', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-message-use-recipient_id', $this->tableName, 'recipient_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-message-user-created_by', $this->tableName);
        $this->dropForeignKey('fk-message-use-recipient_id', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
