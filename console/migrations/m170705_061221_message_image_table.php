<?php

use yii\db\Migration;

class m170705_061221_message_image_table extends Migration
{
    private $tableName = '{{%message_image}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'              => $this->primaryKey(),
            'message_id'      => $this->integer(11)->notNull(),
            'src'             => $this->string(255)->notNull(),
            'sort'            => $this->integer(),
            'is_upload_to_s3' => $this->integer(1)->defaultValue(0),
            'created_at'      => $this->integer(),
            'updated_at'      => $this->integer()
        ]);

        $this->addForeignKey('fk-message_image-message', $this->tableName, 'message_id', 'message', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-message_image-message', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
