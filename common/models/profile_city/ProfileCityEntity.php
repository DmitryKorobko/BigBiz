<?php
namespace common\models\profile_city;

use yii\db\ActiveRecord;

/**
 * Class ProfileCityEntity
 * Product_City model. Binds tabels CityEntity and Profile
 *
 * @package common\models\profile_city
 *
 * @property integer $id
 * @property integer $profile_id
 * @property integer $city_id
 */
class ProfileCityEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile_city}}';
    }

    public function rules()
    {
        return [
            [['profile_id', 'city_id'], 'safe']
        ];
    }
}

