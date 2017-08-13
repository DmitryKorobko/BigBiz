<?php

use yii\db\Migration;

/**
 * Handles the creation of table `main_category_section`.
 */
class m170130_084444_create_main_category_section_table extends Migration
{
    private $tableName = '{{%main_category_section}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(255)->notNull(),
            'sort'       => $this->integer(11),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
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
