<?php

namespace rest\modules\api\v1\content\controllers\actions\category;

use Yii;
use yii\rest\Action;
use common\models\child_category_section\ChildCategorySectionEntity;
use common\behaviors\ValidateGetParameters;
use yii\data\ActiveDataProvider;

/**
 * Class ListChild Action
 *
 * @mixin ValidateGetParameters
 * @package rest\modules\api\v1\content\controllers\actions\category
 */
class ListChildAction extends Action
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
                'inputParams' => [
                    'parent_id'
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting list of child categories
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function run(): ActiveDataProvider
    {
        /** @var  $category ChildCategorySectionEntity.php */
        $category = new $this->modelClass;

        return $category->getListChildCategories(Yii::$app->request->queryParams);
    }
}
