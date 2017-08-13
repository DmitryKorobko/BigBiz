<?php

use yii\db\Migration;

class m170119_200853_create_fixture_for_guest_user extends Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'id'                   => 1,
            'auth_key'             => Yii::$app->security->generateRandomString(),
            'password_hash'        => Yii::$app->security->generatePasswordHash(Yii::$app->params['guest-password']),
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'email'                => Yii::$app->params['guest-email'],
            'status'               => \common\models\user\UserEntity::STATUS_GUEST,
            'created_at'           => time(),
            'updated_at'           => time()
        ]);

        $this->insert('{{%user_profile}}', [
            'id'                 => 1,
            'user_id'            => 1,
            'nickname'           => 'guest',
            'show_dob'           => 0,
            'show_status_online' => 0,
            'push_notification'  => 0,
            'terms_confirm'      => 1,
            'created_at'         => time(),
            'updated_at'         => time()
        ]);

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'user',
            'user_id'   => 1
        ]);
    }

    public function down()
    {
        $this->delete('{{%auth_assignment}}', ['user_id' => 1]);
        $this->delete('{{%user_profile}}', ['id' => 1]);
        $this->delete('{{%user}}', ['id' => 1]);
    }
}
