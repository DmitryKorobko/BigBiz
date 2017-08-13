<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use common\{
    models\theme\ThemeEntity, behaviors\AccessUserBehavior
};
use yii\rest\Action;

/**
 * Class GetProfileAction Action
 *
 * @mixin AccessUserBehavior
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class ListFavoritesAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

            [
                'class'   => AccessUserBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * List favorite themes action
     *
     * @return array|\yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var  $themeModel ThemeEntity */
        $themeModel = new $this->modelClass();

        return $themeModel->getFavoritesThemesByUser();
    }
}
