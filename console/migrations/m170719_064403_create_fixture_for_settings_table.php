<?php

use yii\db\Migration;

class m170719_064403_create_fixture_for_settings_table extends Migration
{
    private $tableName = '{{%settings}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $configs = [
            'supportEmail'                  => 'admin@bigbiz.pp.ua',
            'adminEmail'                    => 'admin@bigbiz.pp.ua',
            'user.passwordResetTokenExpire' => 3600,
            'default_rest_api_limit'        => 5,
            's3_folders_user_profile'       => 'user_profile',
            's3_folders_shop_profile'       => 'shop_profile',
            'shopsPerPage'                  => 8,
            'productsPerPage'               => 12,
            'themesPerPage'                 => 6,
            'reviewsPerPage'                => 6
        ];

        $rows = [];
        foreach ($configs as $key => $value) {
            $rows[] = [
                'key'   => $key,
                'value' => $value
            ];
        }

        $this->batchInsert($this->tableName, ['key', 'value'], $rows);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->truncateTable($this->tableName);
    }
}
