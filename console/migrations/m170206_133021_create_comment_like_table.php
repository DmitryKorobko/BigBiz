<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment_like_dislike`.
 */
class m170206_133021_create_comment_like_table extends Migration
{
    private $tableName = '{{%comment_like}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'comment_id' => $this->integer(11)->notNull(),
            'user_id'    => $this->integer(1)->notNull(),
            'like'       => $this->integer(1)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('fk-comment_like-comment', $this->tableName, 'comment_id',
            'comment', 'id', 'CASCADE');

        $this->addForeignKey('fk-comment_like-user', $this->tableName, 'user_id',
            'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-comment_like-comment', $this->tableName);
        $this->dropForeignKey('fk-comment_like-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
