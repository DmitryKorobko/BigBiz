<?php
namespace rest\modules\api\v1\content\controllers;

use yii\{ filters\auth\HttpBearerAuth, rest\ActiveController };
use common\models\{ child_category_section\ChildCategorySectionEntity, main_category_section\MainCategorySectionEntity };
use rest\modules\api\v1\content\controllers\actions\category\{ ListMainAction, ListChildAction };

/**
 * Class CategoryController
 *
 * @package rest\modules\api\v1\content\controllers
 */
class CategoryController extends ActiveController
{
    /** @var  $modelClass MainCategorySectionEntity.php */
    public $modelClass = MainCategorySectionEntity::class;

    /**
     * @var array
     */
    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['list-main'] = [
            'class'      => ListMainAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['list-child'] = [
            'class'      => ListChildAction::class,
            'modelClass' => ChildCategorySectionEntity::class,
        ];

        return $actions;
    }
}
