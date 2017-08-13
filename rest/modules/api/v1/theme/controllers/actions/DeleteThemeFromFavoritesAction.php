<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use common\{
    models\user_theme_favorite\UserThemeFavoriteEntity, behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class DeleteThemeFromFavoritesAction Action
 *
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class DeleteThemeFromFavoritesAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Delete theme from favorite list action
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function run($id): array
    {
        /** @var  $model UserThemeFavoriteEntity */
        $model = UserThemeFavoriteEntity::findOne(['user_id' => Yii::$app->user->identity->getId(), 'theme_id' => $id]);
        if (! $model) {
            throw new NotFoundHttpException('Тема не найдена');
        }

        if ($model && $model->delete() === false) {
            throw new ServerErrorHttpException('Произошла ошибка. 
                Повторите попытку или сообщите об ошибке администарации приложения.');
        }

        Yii::$app->getResponse()->setStatusCode(200, 'OK');
        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => 'Тема успешно удалена из избранного',
            'data'    => ['theme_id' => (int) $id]
        ];
    }
}
