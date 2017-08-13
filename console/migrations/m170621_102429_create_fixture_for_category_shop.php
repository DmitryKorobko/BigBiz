<?php

use yii\db\Migration;

class m170621_102429_create_fixture_for_category_shop extends Migration
{
    private $tableName = '{{%main_category_section}}';
    
    public function up()
    {
        $this->insert($this->tableName,
            [
                'name'          => 'Магазины',
                'sort'          => 3,
                'category_type' => 'shop',
                'created_at'    => time(),
                'updated_at'    => time()
            ]
        );

    }

    public function down()
    {
        $this->truncateTable($this->tableName);
    }
}
