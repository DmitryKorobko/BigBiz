<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                    => $this->primaryKey(),
            'auth_key'              => $this->string(32)->notNull(),
            'password_hash'         => $this->string()->notNull(),
            'password_reset_token'  => $this->string()->unique(),
            'email'                 => $this->string()->notNull()->unique(),
            'recovery_code'         => $this->string()->null(),
            'created_recovery_code' => $this->integer()->null(),
            'verification_code'     => $this->integer()->null(),
            'status'                => "ENUM('VERIFIED', 'UNVERIFIED', 'DELETED', 'GUEST', 'BANNED') DEFAULT 'UNVERIFIED'",
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
