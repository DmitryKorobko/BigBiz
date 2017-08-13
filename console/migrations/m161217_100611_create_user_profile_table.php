<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 */
class m161217_100611_create_user_profile_table extends Migration
{
    private $tableName = '{{%user_profile}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'                 => $this->primaryKey(),
            'user_id'            => $this->integer(11)->notNull(),
            'nickname'           => $this->string(255)->notNull(),
            'avatar'             => $this->text(),
            'gender'             => $this->string(255)->null(),
            'status_message'     => $this->string(50)->null(),
            'dob_day'            => $this->integer(11)->null(),
            'dob_month'          => $this->integer(11)->null(),
            'dob_year'           => $this->integer(11)->null(),
            'show_dob'           => $this->integer(1)->defaultValue(1),
            'show_status_online' => $this->integer(1)->defaultValue(1),
            'push_notification'  => $this->integer(1)->notNull()->defaultValue(1),
            'terms_confirm'      => $this->integer(1)->notNull(),
            'created_at'         => $this->integer()->unsigned()->defaultValue(null),
            'updated_at'         => $this->integer()->unsigned()->defaultValue(null)
        ]);

        $this->addForeignKey('fk-user_profile_user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_profile_user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
