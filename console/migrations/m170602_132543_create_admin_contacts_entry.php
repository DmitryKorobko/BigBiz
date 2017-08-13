<?php

use yii\db\Migration;
use common\models\user\UserEntity;

class m170602_132543_create_admin_contacts_entry extends Migration
{
    private $tableName = '{{%admin_contact}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $boss = UserEntity::find()->where(['user.email' => 'admin-bigbiz@gmail.com'])->asArray()->one();
        $this->truncateTable($this->tableName);
        $this->insert($this->tableName, [
            'is_boss'    => 1,
            'user_id'    => $boss['id'],
            'nickname'   => null,
            'avatar'     => null,
            'skype'      => null,
            'viber'      => null,
            'telegram'   => null,
            'jabber'     => null,
            'vipole'     => null,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable($this->tableName);
    }
}
