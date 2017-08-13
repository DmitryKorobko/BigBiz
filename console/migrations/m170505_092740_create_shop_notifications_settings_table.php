<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_notifications_settings`.
 */
class m170505_092740_create_shop_notifications_settings_table extends Migration
{
    private $tableName = '{{%shop_notifications_settings}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'                   => $this->primaryKey(),
            'user_id'              => $this->integer(11)->notNull(),
            'new_personal_message' => $this->integer(1)->defaultValue(1),
            'new_review'           => $this->integer(1)->defaultValue(1),
            'new_reply_comment'    => $this->integer(1)->defaultValue(1),
            'new_product_report'   => $this->integer(1)->defaultValue(1),
            'new_theme_comment'    => $this->integer(1)->defaultValue(1),
            'theme_was_verified'   => $this->integer(1)->defaultValue(1),
            'new_like'             => $this->integer(1)->defaultValue(1),
            'messages_to_email'    => $this->integer(1)->defaultValue(1),
            'site_dispatch'        => $this->integer(1)->defaultValue(1),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-shop_notifications_settings_user', $this->tableName, 'user_id',
            'user', 'id', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_notifications_settings_user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
