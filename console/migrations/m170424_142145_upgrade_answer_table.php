<?php

use yii\db\Migration;

class m170424_142145_upgrade_answer_table extends Migration
{
    private $tableName = '{{%answer}}';

    public function safeUp()
    {
        $this->alterColumn($this->tableName,'type',"ENUM(
                'LIKE_THEME', 'LIKE_COMMENT', 'REPLY_COMMENT', 'NEW_PRODUCT_REPORT', 'THEME_WAS_VERIFIED',
                'NEW_THEME_COMMENT', 'NEW_PRODUCT_COMMENT', 'NEW_USER_REPUTATION', 'NEW_SHOP_REVIEW')"
        );
    }

    public function safeDown()
    {
        $this->alterColumn($this->tableName,'type',"ENUM(
                'LIKE_THEME', 'LIKE_COMMENT', 'REPLY_COMMENT', 'NEW_PRODUCT_REPORT',
                'NEW_THEME_COMMENT', 'NEW_PRODUCT_COMMENT', 'NEW_USER_REPUTATION', 'NEW_SHOP_REVIEW')"
        );
    }
}