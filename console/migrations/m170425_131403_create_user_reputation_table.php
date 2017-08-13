<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_reputation`.
 */
class m170425_131403_create_user_reputation_table extends Migration
{
    private $tableName = '{{%user_reputation}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'                 => $this->primaryKey(),
            'recipient_id'       => $this->integer(11)->notNull(),
            'created_by'         => $this->integer(11)->notNull(),
            'text'               => $this->string(100)->null(),
            'created_at'         => $this->integer()->notNull(),
            'updated_at'         => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-user_reputation_recipient_id', $this->tableName, 'recipient_id','user',
            'id', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_reputation_created_by', $this->tableName, 'created_by','user',
            'id', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user_reputation_recipient_id', $this->tableName);
        $this->dropForeignKey('fk-user_reputation_created_by', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
