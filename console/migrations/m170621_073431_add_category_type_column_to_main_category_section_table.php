<?php

use yii\db\Migration;

/**
 * Handles adding category_type to table `main_category_section`.
 */
class m170621_073431_add_category_type_column_to_main_category_section_table extends Migration
{
    private $tableName = '{{%main_category_section}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'category_type', "ENUM('shop', 'default')");
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'category_type');
    }
}
