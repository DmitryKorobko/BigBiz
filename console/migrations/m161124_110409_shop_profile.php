<?php

use yii\db\Migration;

class m161124_110409_shop_profile extends Migration
{
    private $tableName = '{{%shop_profile}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'              => $this->primaryKey(),
            'name'            => $this->string()->notNull(),
            'status_text'     => $this->string(255)->null(),
            'description'     => $this->string(),
            'image'           => $this->string(),
            'site_url'        => $this->string()->unique(),
            'work_start_time' => $this->string(16)->notNull()->defaultValue('08:00'),
            'work_end_time'   => $this->string(16)->notNull()->defaultValue('17:00'),
            'category_start'  => $this->integer()->null(),
            'category_end'    => $this->integer()->null(),
            'skype'           => $this->string()->unique(),
            'viber'           => $this->string()->unique(),
            'telegram'        => $this->string()->unique(),
            'jabber'          => $this->string()->unique(),
            'vipole'          => $this->string()->unique(),
            'user_id'         => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey('fk-shop_profile_user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-shop_profile_user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
