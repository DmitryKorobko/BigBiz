<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m170121_213754_create_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('settings', [
            'id'    => $this->primaryKey(),
            'key'   => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull()
        ]);

        $this->insert('settings', [
            'key'   => 'website_banner_price',
            'value' => 50
        ]);
        $this->insert('settings', [
            'key'   => 'mobile_banner_price',
            'value' => 50
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('settings');
    }
}
