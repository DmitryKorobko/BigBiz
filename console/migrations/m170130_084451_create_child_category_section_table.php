<?php

use yii\db\Migration;

/**
 * Handles the creation of table `child_category_section`.
 */
class m170130_084451_create_child_category_section_table extends Migration
{
    private $tableName = '{{%child_category_section}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'                     => $this->primaryKey(),
            'name'                   => $this->string(255)->notNull(),
            'sort'                   => $this->integer(11)->notNull()->defaultValue(1),
            'permissions_only_admin' => $this->integer(1)->notNull()->defaultValue(0),
            'parent_category_id'     => $this->integer(11)->notNull(),
            'created_at'             => $this->integer(),
            'updated_at'             => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-child_category_section-main_category_section',
            $this->tableName,
            'parent_category_id',
            'main_category_section',
            'id',
            'RESTRICT'
        );
        $this->createIndex('idx_parent_category_id', $this->tableName, ['parent_category_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-child_category_section-main_category_section', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
