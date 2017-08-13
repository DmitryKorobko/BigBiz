<?php

use yii\db\Migration;

/**
 * Handles adding refresh_token to table `user`.
 */
class m170606_134227_add_refresh_token_column_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'refresh_token', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('user', 'refresh_token', $this->string()->notNull());
    }
}
