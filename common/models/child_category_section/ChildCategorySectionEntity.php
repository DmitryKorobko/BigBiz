<?php
namespace common\models\child_category_section;

use yii\{ behaviors\TimestampBehavior, data\ActiveDataProvider, db\ActiveRecord };
use common\models\child_category_section\repositories\FrontendChildCategorySectionRepository;

/**
 * Class ChildCategorySectionEntity
 *
 * @package common\models\child_category_section
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $permissions_only_admin
 * @property integer $parent_category_id
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 */
class ChildCategorySectionEntity extends ActiveRecord
{
    use FrontendChildCategorySectionRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%child_category_section}}';
    }
    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'name', 'description', 'parent_category_id', 'permissions_only_admin', 'sort'
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'name', 'description', 'parent_category_id', 'permissions_only_admin', 'sort'
        ];
        return $scenarios;
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
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['name', 'parent_category_id', 'description'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['name', 'description'], 'string'],
            [['sort', 'parent_category_id', 'permissions_only_admin'], 'integer'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'                     => '#',
            'name'                   => 'Название категории',
            'description'            => 'Описание категории',
            'permissions_only_admin' => 'Создавать темы в данной категории могут только администратор',
            'parent_category_id'     => 'Родительская категория',
            'sort'                   => 'Сортировка',
            'created_at'             => 'Дата создания',
            'updated_at'             => 'Дата изменения'
        ];
    }
    /**
     * Method of getting list child categories with filters. Using for GridView
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider 
    {
        $query = self::find()->orderBy(['sort' => SORT_ASC]);
        $dataProvider = new  ActiveDataProvider([
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
        if (isset($params['ChildCategorySectionEntity'])) {
            $query->andFilterWhere(['like', 'parent_category_id',
                $params['ChildCategorySectionEntity']['parent_category_id']]);
        }
        return $dataProvider;
    }
    /**
     * Method of getting list child categories. Using for Rest API
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function getListChildCategories($params): ActiveDataProvider
    {
        $query = self::find()->select(['id', 'name', 'description', 'sort'])
            ->where(['parent_category_id' => $params['parent_id']]);
        if (isset($params['user_category'])) {
            $query->andWhere(['permissions_only_admin' => 0]);
        }
        $query->orderBy(['sort' => SORT_ASC]);
        $dataProvider = new  ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return $dataProvider;
    }
}