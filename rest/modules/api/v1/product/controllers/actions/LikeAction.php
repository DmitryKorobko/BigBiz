<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\models\product_like\ProductLikeEntity;
use common\behaviors\{
    ValidatePostParameters, AccessUserStatusBehavior, ValidationExceptionFirstMessage
};
use Yii;
use yii\{
    web\HttpException, rest\Action, web\ServerErrorHttpException
};

/**
 * Class LikeAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class LikeAction extends Action
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
                'inputParams' => ['like', 'product_id']
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun()
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Like products action
     *
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $postData = Yii::$app->getRequest()->getBodyParams();

        /** @var  $productLike ProductLikeEntity */
        $productLike = new ProductLikeEntity;
        if (!$productLike->validate('like')) {
            ValidationExceptionFirstMessage::throwModelException($productLike->errors);
        }
        if ($productLike->likeProductByUser($postData['product_id'], $postData['like'])) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode('200', 'OK');
            return $response->content = [
                'status'  => $response->statusCode,
                'message' => 'Лайк поставлен'
            ];
        }

        throw new ServerErrorHttpException('Произошла ошибка.
            Повторите попытку или сообщите об ошибке администарации приложения');
    }
}