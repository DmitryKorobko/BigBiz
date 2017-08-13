<?php

use yii\db\Migration;

class m170705_062117_edit_message_table extends Migration
{
    private $tableName = '{{%message}}';

    public function up()
    {
        $this->dropColumn($this->tableName, 'image');
    }

    public function down()
    {
        $this->addColumn($this->tableName, 'image', $this->text()->null());
    }

}
