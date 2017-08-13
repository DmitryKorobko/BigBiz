<?php

use yii\db\Migration;

class m170519_144454_expand_auth_item_table extends Migration
{
    private $tableName = '{{%auth_item}}';

    public function safeUp()
    {
        $this->insert($this->tableName,
            [
                'name' => 'moder',
                'type' => 1,
                'description' => 'Moder Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );
    }

    public function safeDown()
    {
        $this->truncateTable($this->tableName);
    }
}
