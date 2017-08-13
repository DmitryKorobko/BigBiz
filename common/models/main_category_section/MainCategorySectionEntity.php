<?php
namespace common\models\main_category_section;

use yii\{ behaviors\TimestampBehavior, data\ActiveDataProvider, db\ActiveRecord };
use common\models\main_category_section\repositories\FrontendMainCategorySectionRepository;

/**
 * Class MainCategorySectionEntity
 *
 * @package common\models\main_category_section
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 */
class MainCategorySectionEntity extends ActiveRecord
{
    use FrontendMainCategorySectionRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%main_category_section}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'sort'];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'sort'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['name'], 'string'],
            [['sort'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => '#',
            'name'       => 'Название категории',
            'sort'       => 'Сортировка',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения'
        ];
    }

    /**
     * Method of getting list main categories. Using for GridView and Rest API
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = self::find()->orderBy(['sort' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);

        $query->andWhere('name LIKE "%' . $this->name . '%"');

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
