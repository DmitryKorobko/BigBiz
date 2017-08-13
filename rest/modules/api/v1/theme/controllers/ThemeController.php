<?php
namespace rest\modules\api\v1\theme\controllers;

use common\models\{
    theme\ThemeEntity, user_theme_favorite\UserThemeFavoriteEntity
};
use rest\modules\api\v1\theme\controllers\actions\{
    DetailAction, LikeDislikeAction, ListAction, ShopThemesAction, AddThemeInFavoritesAction,
    DeleteThemeFromFavoritesAction, ListFavoritesAction
};
use yii\{ filters\auth\HttpBearerAuth, rest\ActiveController };

/**
 * Class ThemeController
 *
 * @package rest\modules\api\v1\shop\controllers
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

        $actions['list'] = [
            'class'      => ListAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['detail'] = [
            'class'      => DetailAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['like-dislike'] = [
            'class'      => LikeDislikeAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['shop-themes'] = [
            'class'      => ShopThemesAction::class,
            'modelClass' => ThemeEntity::class
        ];

        $actions['add-favorite'] = [
            'class'      => AddThemeInFavoritesAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete-favorite'] = [
            'class'      => DeleteThemeFromFavoritesAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['list-favorites'] = [
            'class'      => ListFavoritesAction::class,
            'modelClass' => ThemeEntity::class
        ];

        return $actions;
    }

}