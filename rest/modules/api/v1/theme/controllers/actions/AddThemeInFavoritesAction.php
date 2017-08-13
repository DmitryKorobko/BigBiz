<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use common\behaviors\{
    ValidatePostParameters, AccessUserStatusBehavior, ValidationExceptionFirstMessage
};
use Yii;
use yii\{
    rest\Action, web\BadRequestHttpException, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class AddThemeInFavorites Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class AddThemeInFavoritesAction extends Action
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
                'inputParams' => ['theme_id']
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Add theme in favorite list action
     *
     * @return array|\yii\db\ActiveRecord
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass();

        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['user_id'] = Yii::$app->user->identity->getId();

        $model->load($postData, '');
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201, 'Created');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Тема в избранное успешно добавлена',
                'data'    => $model->getAttributes()
            ];
        } elseif ($model->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($model->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. 
            Повторите попытку или сообщите об ошибке администарации приложения.');
    }
}
