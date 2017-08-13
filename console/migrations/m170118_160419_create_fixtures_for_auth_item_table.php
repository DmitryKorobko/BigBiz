<?php

use yii\db\Migration;

/**
 * Handles the creation of table `fixtures_for_auth_item`.
 */
class m170118_160419_create_fixtures_for_auth_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert('{{%auth_item}}',
            [
                'name' => 'admin',
                'type' => 1,
                'description' => 'Admin Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );

        $this->insert('{{%auth_item}}',
            [
                'name' => 'user',
                'type' => 1,
                'description' => 'User Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );

        $this->insert('{{%auth_item}}',
            [
                'name' => 'shop',
                'type' => 1,
                'description' => 'Shop Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->truncateTable('{{%auth_item}}');
    }
}
