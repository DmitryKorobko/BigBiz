<?php

use yii\db\Migration;

class m170727_145903_edit_fixture_for_guest_user_status extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->update($this->tableName,
            ['status' => \common\models\user\UserEntity::STATUS_UNVERIFIED],
            ['email' => \Yii::$app->params['guest-email']]
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->truncateTable($this->tableName);
    }
}
