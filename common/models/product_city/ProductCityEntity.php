<?php

namespace common\models\product_city;

use yii\db\ActiveRecord;

/**
 * Class ProductCityEntity
 *
 * @package common\models\product_city
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $city_id
 *
 */
class ProductCityEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_city}}';
    }

    public function rules()
    {
        return [
            [['product_id', 'city_id'], 'safe']
        ];
    }
}