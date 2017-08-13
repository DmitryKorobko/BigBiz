<?php

use yii\db\Migration;

class m170131_141335_theme extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%theme}}', [
            'id'                  => $this->primaryKey(),
            'name'                => $this->string()->notNull(),
            'description'         => $this->text()->notNull(),
            'image'               => $this->string()->null(),
            'view_count'          => $this->integer()->defaultValue(0),
            'comments_count'      => $this->integer()->defaultValue(0),
            'new_comments_count'  => $this->integer()->notNull()->defaultValue(0),
            'user_id'             => $this->integer()->notNull(),
            'category_id'         => $this->integer()->null(),
            'date_of_publication' => $this->integer()->notNull()->defaultValue(0),
            'sort'                => $this->integer()->defaultValue(1),
            'status'              => "ENUM('VERIFIED', 'UNVERIFIED', 'REJECTED')",
            'created_at'          => $this->integer()->notNull(),
            'updated_at'          => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-theme-user_id',
            'theme',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-theme-category_id',
            'theme',
            'category_id',
            'child_category_section',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-theme-shop_id', '{{%theme}}');
        $this->dropForeignKey('fk-theme-category_id', '{{%theme}}');
        $this->dropTable('{{%theme}}');
    }
}
