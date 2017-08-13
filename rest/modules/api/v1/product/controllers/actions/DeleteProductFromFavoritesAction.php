<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\{
    models\user_product_favorite\UserProductFavoriteEntity,
    behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class DeleteProductFromFavoritesAction
 *
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class DeleteProductFromFavoritesAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Delete product from favorite list action
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array
    {
        /** @var  $model UserProductFavoriteEntity */
        $model = UserProductFavoriteEntity::findOne(['user_id' => Yii::$app->user->identity->getId(), 'product_id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException('Товар не найден');
        }
        if ($model && $model->delete() === false) {
            throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке
                администарации приложения.');
        }

        Yii::$app->getResponse()->setStatusCode(200, 'OK');
        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => 'Товар успешно удалён из избранного',
            'data'    => ['product_id' => (int) $id]
        ];
    }
}
