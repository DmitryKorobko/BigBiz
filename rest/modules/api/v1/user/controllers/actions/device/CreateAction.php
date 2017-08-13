<?php

namespace rest\modules\api\v1\user\controllers\actions\device;

use common\behaviors\{
    ValidationExceptionFirstMessage, ValidatePostParameters, AccessUserStatusBehavior
};
use common\models\device\DeviceEntity;
use Yii;
use yii\{
    rest\Action, web\ServerErrorHttpException
};

/**
 * Class Create Action
 *
 * @mixin ValidatePostParameters
 * @mixin ValidationExceptionFirstMessage
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\user\controllers\actions\device
 */
class CreateAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['uuid']
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
    protected function beforeRun(): bool
    {
        $this->checkUserRole();
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of registration device of user
     *
     * @return array|\yii\db\ActiveRecord
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $postData = Yii::$app->getRequest()->getBodyParams();
        /** @var  $device DeviceEntity.php */
        $device = DeviceEntity::findOne(['uuid' => $postData['uuid'], 'user_id' => Yii::$app->user->identity->getId()]);
        if ($device) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Девайс успешно изменён',
                'data'    => $device->updateDevice($postData)
            ];
        } else {
            /* @var $model DeviceEntity.php */
            $model = new $this->modelClass();
            if ($model->createDevice($postData)) {
                Yii::$app->getResponse()->setStatusCode(201, 'Created');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Регистрация девайса прошла успешна',
                    'data'    => $model->getAttributes()
                ];
            } elseif ($model->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($model->errors);
            }
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
            ошибке администарации приложения.');
    }
}
