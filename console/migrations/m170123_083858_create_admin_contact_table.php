<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin_contact`.
 */
class m170123_083858_create_admin_contact_table extends Migration
{
    private $tableName = '{{%admin_contact}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'jabber'     => $this->string(255)->null(),
            'vipole'     => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);

        $this->insert($this->tableName, [
            'jabber'     => null,
            'vipole'     => null,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
