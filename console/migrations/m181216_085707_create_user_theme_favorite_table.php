<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_theme_favorite`.
 */
class m181216_085707_create_user_theme_favorite_table extends Migration
{
    private $tableName = '{{%user_theme_favorite}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'theme_id'   => $this->integer(11)->notNull(),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'updated_at' => $this->integer()->unsigned()->defaultValue(null)
        ]);

        $this->addForeignKey('fk-user_theme_favorite-theme', $this->tableName, 'theme_id', 'theme', 'id', 'CASCADE');
        $this->addForeignKey('fk-user_theme_favorite-user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_theme_favorite-theme', $this->tableName);
        $this->dropForeignKey('fk-user_theme_favorite-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
