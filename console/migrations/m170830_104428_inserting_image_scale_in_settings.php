<?php

use yii\db\Migration;

class m170830_104428_inserting_image_scale_in_settings extends Migration
{

    private $tableName = '{{%settings}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $configs = [
            'product_image_min_width'   => 1080,
            'product_image_min_height'  => 640,
            'product_image_max_width'   => 3840,
            'product_image_max_height'  => 2160,
            'theme_image_min_width'     => 1080,
            'theme_image_min_height'    => 640,
            'theme_image_max_width'     => 3840,
            'theme_image_max_height'    => 2160,
            'mobile_banner_min_width'   => 1080,
            'mobile_banner_min_height'  => 640,
            'mobile_banner_max_width'   => 3840,
            'mobile_banner_max_height'  => 2160,
            'website_banner_min_width'  => 1080,
            'website_banner_min_height' => 640,
            'website_banner_max_width'  => 3840,
            'website_banner_max_height' => 2160,
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
