<?php

use yii\db\Migration;

/**
 * Handles the creation of table `device`.
 */
class m170304_202258_create_device_table extends Migration
{
    private $tableName = '{{%device}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'user_id'      => $this->integer()->notNull(),
            'device_type'  => $this->string(255)->null(),
            'make'         => $this->string(255)->null(),
            'os'           => 'ENUM("iOS", "ANDROID")',
            'mode'         => "ENUM('ALLOW', 'DISABLED', 'BLOCKED') DEFAULT 'ALLOW'",
            'version'      => $this->string(255)->null(),
            'uuid'         => $this->string(255)->notNull(),
            'device_token' => $this->string(255)->notNull(),
            'created_at'   => $this->integer(),
            'updated_at'   => $this->integer()
        ]);

        $this->addForeignKey('fk-message-user-user_id', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-message-user-user_id', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
