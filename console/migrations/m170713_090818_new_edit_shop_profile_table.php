<?php

use yii\db\Migration;

class m170713_090818_new_edit_shop_profile_table extends Migration
{
    private $tableName = '{{%shop_profile}}';

    public function safeUp()
    {
        $this->dropIndex('site_url', $this->tableName);
        $this->dropIndex('skype', $this->tableName);
        $this->dropIndex('viber', $this->tableName);
        $this->dropIndex('telegram', $this->tableName);
        $this->dropIndex('jabber', $this->tableName);
        $this->dropIndex('vipole', $this->tableName);
        $this->alterColumn($this->tableName, 'site_url',$this->string());
        $this->alterColumn($this->tableName, 'skype',$this->string());
        $this->alterColumn($this->tableName, 'viber',$this->string());
        $this->alterColumn($this->tableName, 'telegram',$this->string());
        $this->alterColumn($this->tableName, 'jabber',$this->string());
        $this->alterColumn($this->tableName, 'vipole',$this->string());
    }

    public function safeDown()
    {
        $this->alterColumn($this->table, 'site_url', $this->string()->unique());
        $this->alterColumn($this->table, 'skype', $this->string()->unique());
        $this->alterColumn($this->table, 'viber', $this->string()->unique());
        $this->alterColumn($this->table, 'telegram', $this->string()->unique());
        $this->alterColumn($this->table, 'jabber', $this->string()->unique());
        $this->alterColumn($this->table, 'vipole', $this->string()->unique());
    }
}