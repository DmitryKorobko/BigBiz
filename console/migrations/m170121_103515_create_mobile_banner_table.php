<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mobile_banner`.
 */
class m170121_103515_create_mobile_banner_table extends Migration
{
    private $tableName = '{{%mobile_banner}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'image'       => $this->string(255)->notNull(),
            'user_id'     => $this->integer(11)->notNull(),
            'start_date'  => $this->integer(11)->notNull(),
            'end_date'    => $this->integer(11)->notNull(),
            'status'     => $this->integer(1)->defaultValue(0),
            'created_at'  => $this->integer()->unsigned()->defaultValue(null),
            'updated_at'  => $this->integer()->unsigned()->defaultValue(null)
        ]);

        $this->addForeignKey('fk-mobile_banner-user', $this->tableName, 'user_id', 'user', 'id', 'RESTRICT');
        $this->createIndex('idx_user_id', $this->tableName, ['user_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-mobile_banner-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
