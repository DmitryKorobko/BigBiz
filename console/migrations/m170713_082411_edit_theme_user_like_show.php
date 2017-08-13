<?php

use yii\db\Migration;

class m170713_082411_edit_theme_user_like_show extends Migration
{
    private $tableName = '{{%theme_user_like_show}}';

    public function up()
    {
        $this->alterColumn($this->tableName, 'show', $this->integer(1)->defaultValue(1));
    }

    public function down()
    {
        $this->alterColumn($this->tableName, 'show', $this->integer(1)->notNull());
    }
}
