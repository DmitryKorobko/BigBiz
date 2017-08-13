<?php

use yii\db\Migration;

/**
 * Handles the creation of table `website_banner`.
 */
class m170121_103506_create_website_banner_table extends Migration
{
    private $tableName = '{{%website_banner}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'image'      => $this->string(255)->notNull(),
            'user_id'    => $this->integer(11)->notNull(),
            'start_date' => $this->integer(11)->notNull(),
            'end_date'   => $this->integer(11)->notNull(),
            'status'     => $this->integer(1)->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'updated_at' => $this->integer()->unsigned()->defaultValue(null)
        ]);

        $this->addForeignKey('fk-website_banner-user', $this->tableName, 'user_id', 'user', 'id', 'RESTRICT');
        $this->createIndex('idx_user_id', $this->tableName, ['user_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-website_banner-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
