<?php

use yii\db\Migration;

class m170127_105958_create_fixtures_for_admin extends Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'id'                   => 2,
            'auth_key'             => Yii::$app->security->generateRandomString(),
            'password_hash'        => Yii::$app->security->generatePasswordHash(Yii::$app->params['admin-password']),
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'email'                => Yii::$app->params['admin-email'],
            'status'               => \common\models\user\UserEntity::STATUS_VERIFIED,
            'created_at'           => time(),
            'updated_at'           => time()
        ]);

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'admin',
            'user_id'   => 2
        ]);
    }

    public function down()
    {
        $this->delete('{{%auth_assignment}}', ['user_id' => 2]);
        $this->delete('{{%user}}', ['id' => 2]);
    }
}
