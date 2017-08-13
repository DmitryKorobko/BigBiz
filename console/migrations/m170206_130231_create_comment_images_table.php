<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment_images`.
 */
class m170206_130231_create_comment_images_table extends Migration
{
    private $tableName = '{{%comment_image}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'              => $this->primaryKey(),
            'comment_id'      => $this->integer(11)->notNull(),
            'src'             => $this->string(255)->notNull(),
            'sort'            => $this->integer(),
            'is_upload_to_s3' => $this->integer(1)->defaultValue(0),
            'created_at'      => $this->integer(),
            'updated_at'      => $this->integer()
        ]);

        $this->addForeignKey('fk-comment_image-comment', $this->tableName, 'comment_id', 'comment', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-comment_image-comment', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
