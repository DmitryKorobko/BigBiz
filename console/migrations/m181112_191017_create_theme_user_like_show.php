<?php

use yii\db\Migration;

class m181112_191017_create_theme_user_like_show extends Migration
{
    private $tableName = '{{%theme_user_like_show}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'theme_id'   => $this->integer(11)->notNull(),
            'like'       => $this->integer(1)->defaultValue(0),
            'show'       => $this->integer(1)->notNull(),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'updated_at' => $this->integer()->unsigned()->defaultValue(null)
        ]);

        $this->addForeignKey('fk-theme_user_like_show-theme', $this->tableName, 'theme_id', 'theme', 'id', 'CASCADE');
        $this->addForeignKey('fk-theme_user_like_show-user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-theme_user_like_show-theme', $this->tableName);
        $this->dropForeignKey('fk-theme_user_like_show-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
