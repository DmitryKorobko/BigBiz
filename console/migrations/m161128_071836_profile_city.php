<?php

use yii\db\Migration;

class m161128_071836_profile_city extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%profile_city}}', [
            'id'         => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'city_id'    => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-profile_city-shop_profile',
            'profile_city',
            'profile_id',
            'shop_profile',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-profile_city-city',
            'profile_city',
            'city_id',
            'city',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-profile_city-city', '{{%profile_city}}');
        $this->dropForeignKey('fk-profile_city-shop_profile', '{{%profile_city}}');
        $this->dropTable('{{%profile_city}}');
    }
}
