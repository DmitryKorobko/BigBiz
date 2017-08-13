<?php

use yii\db\Migration;

class m170529_095300_expand_admin_contact_table extends Migration
{
    private $tableName = '{{%admin_contact}}';

    public function safeUp()
    {
        $this->truncateTable($this->tableName);
        $this->addColumn($this->tableName, 'is_boss', $this->integer(1)->defaultValue(0));
        $this->addColumn($this->tableName, 'user_id', $this->integer(11)->notNull());
        $this->addColumn($this->tableName, 'nickname', $this->string(255)->null());
        $this->addColumn($this->tableName, 'avatar', $this->string(255)->null());
        $this->addColumn($this->tableName, 'skype', $this->string(255)->null());
        $this->addColumn($this->tableName, 'viber', $this->string(255)->null());
        $this->addColumn($this->tableName, 'telegram', $this->string(255)->null());

        $this->addForeignKey(
            'fk-admin_contact_user', $this->tableName, 'user_id','user',
            'id', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->truncateTable($this->tableName);
        $this->dropForeignKey('fk-admin_contact_user', $this->tableName);
        $this->dropColumn($this->tableName, 'is_boss');
        $this->dropColumn($this->tableName, 'user_id');
        $this->dropColumn($this->tableName, 'nickname');
        $this->dropColumn($this->tableName, 'avatar');
        $this->dropColumn($this->tableName, 'skype');
        $this->dropColumn($this->tableName, 'viber');
        $this->dropColumn($this->tableName, 'telegram');
        $this->insert($this->tableName, [
            'jabber'     => null,
            'vipole'     => null,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }
}
