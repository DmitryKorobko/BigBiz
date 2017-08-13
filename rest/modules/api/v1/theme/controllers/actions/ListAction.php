<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use Yii;
use common\{
    models\child_category_section\ChildCategorySectionEntity, models\theme\ThemeEntity, behaviors\ValidateGetParameters
};
use yii\{
    rest\Action, data\ArrayDataProvider, web\NotFoundHttpException
};

/**
 * Class List Action
 *
 * @mixin ValidateGetParameters
 *
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class ListAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['category_id']
            ],
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting list of themes in category
     *
     * @return \yii\data\ArrayDataProvider
     * @throws NotFoundHttpException
     */
    public function run(): ArrayDataProvider
    {
        /** @var  $category ChildCategorySectionEntity.php */
        $category = ChildCategorySectionEntity::findOne(['id' => Yii::$app->request->queryParams['category_id']]);
        if (!$category) {
            throw new NotFoundHttpException('Категория не найдена.');
        }

        /** @var  $theme ThemeEntity.php */
        $theme = new $this->modelClass;
        return $theme->getListCategoryThemes(Yii::$app->request->queryParams);
    }
}