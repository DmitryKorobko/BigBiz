<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_confidentiality`.
 */
class m170428_093009_create_user_confidentiality_table extends Migration
{
    private $tableName = '{{%user_confidentiality}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'                         => $this->primaryKey(),
            'user_id'                    => $this->integer(11)->notNull(),
            'show_date_of_birth'         => $this->integer(1)->defaultValue(1),
            'show_status_online'         => $this->integer(1)->defaultValue(1),
            'view_page_access'           => "ENUM('ALL_USERS', 'REGISTERED_USERS', 'NOBODY')DEFAULT 'ALL_USERS'",
            'send_messages_access'       => "ENUM('ALL_USERS', 'REGISTERED_USERS', 'NOBODY')DEFAULT 'ALL_USERS'",
            'frequency_history_cleaning' => "ENUM(
                'ONE_MINUTE', 'FIVE_MINUTES', 'ONE_HOUR', 'THREE_HOURS', 'FIVE_HOURS', 'TWELVE_HOURS',
                'ONE_DAY', 'SEVEN_DAYS', 'NEVER'
            )DEFAULT 'NEVER'",
            'created_at'                 => $this->integer()->notNull(),
            'updated_at'                 => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-user_confidentiality_user', $this->tableName, 'user_id','user',
            'id', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user_confidentiality_user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}