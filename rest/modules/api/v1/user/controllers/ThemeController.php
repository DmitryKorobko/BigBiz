<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\{
    theme\ThemeEntity, user_theme_favorite\UserThemeFavoriteEntity
};
use rest\modules\api\v1\user\controllers\actions\theme\{
    CreateAction, DeleteAction, ListThemesAction, UpdateAction
};
use yii\{
    filters\auth\HttpBearerAuth, rest\ActiveController
};

/**
 * Class ThemeController
 *
 * @package rest\modules\api\v1\user\controllers
 */
class ThemeController extends ActiveController
{
    /** @var  $modelClass UserThemeFavoriteEntity.php */
    public $modelClass = UserThemeFavoriteEntity::class;

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

        $actions['index'] = [
            'class'      => ListThemesAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['create'] = [
            'class'      => CreateAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['update'] = [
            'class'      => UpdateAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['delete'] = [
            'class'      => DeleteAction::class,
            'modelClass' => ThemeEntity::class
        ];

        return $actions;
    }
}
