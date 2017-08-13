<?php

use yii\db\Migration;

/**
 * Handles the creation of table `feedback`.
 */
class m181201_192737_create_feedback_table extends Migration
{
    private $tableName = '{{%feedback}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'name'       => $this->string(255)->notNull(),
            'message'    => $this->text()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-feedback-user_id',
            'theme',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-feedback-user_id', '{{%feedback}}');
        $this->dropTable($this->tableName);
    }
}
