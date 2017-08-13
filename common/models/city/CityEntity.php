<?php
namespace common\models\city;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class CityEntity
 * CityEntity model. Contents , it's count and total price.
 *
 * @package common\models\city
 *
 * @property integer $id
 * @property integer $name
 */
class CityEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * Table name - city. Includes id, Name and Country.
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => Yii::t('app', 'id'),
            'name'    => Yii::t('app', 'Название')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'unique'],
            [['name'], 'safe'],
        ];
    }

    /**
     * Method of creating few models for saving many to many relationships
     *
     * @param $ids
     * @return array
     */
    public static function createMultipleModels($ids)
    {
        $result = [];
        if ($ids) {
            if (!is_array($ids)) {
                $id[] = $ids;
                $ids = $id;
            }

            foreach ($ids as $id) {
                $result[] = CityEntity::findOne($id);
            }
        }
        return $result;
    }
}
