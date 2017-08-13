<?php
namespace common\models\settings;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class SettingsEntity
 *
 * @package common\models\settings
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class SettingsEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'    => 'ID',
            'key'   => 'Название',
            'value' => 'Значение'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['key', 'value'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['key', 'value'], 'string'],
            [['key', 'value'], 'safe']
        ];
    }

    /**
     * Method of getting list settings
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $dataProvider = new ActiveDataProvider([
            'query'      => self::find(),
            'sort'       => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Method of getting value by key
     *
     * @param $key
     * @return static
     */
    public static function getValueByKey($key)
    {
        return self::findOne(['key' => $key]);
    }
}
