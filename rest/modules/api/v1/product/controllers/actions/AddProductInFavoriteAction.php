<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\{
    behaviors\ValidationExceptionFirstMessage, models\user_product_favorite\UserProductFavoriteEntity,
    behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{rest\Action, web\ServerErrorHttpException};

/**
 * Class AddProductInFavoriteAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class AddProductInFavoriteAction extends Action
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
                'class'       => ValidatePostParameters::className(),
                'inputParams' => [
                    'product_id'
                ]
            ],
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
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Method of adding product in favorite list action
     *
     * @return array|UserProductFavoriteEntity
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['user_id'] = Yii::$app->user->identity->getId();

        /* @var $model UserProductFavoriteEntity */
        $model = new $this->modelClass();
        $model->setScenario('create');
        $model->load($postData, '');
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Продукт успешно добавлен в избранное',
                'data'    => $model->getAttributes()
            ];
        } elseif ($model->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($model->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка.
            Повторите попытку или сообщите об ошибке администарации приложения.');
    }
}
