<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\{
    behaviors\AccessUserStatusBehavior, behaviors\ValidateGetParameters, models\message\MessageEntity
};
use Yii;
use yii\rest\Action;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException
};

/**
 * Class DeleteChat Action
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserStatusBehavior
 * @package rest\modules\api\v1\message\controllers\actions
 */
class DeleteChatAction extends Action
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
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['recipient_id']
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
    protected function beforeRun()
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of deleting chat history
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException'
     */
    public function run()
    {
        /** @var  $messages MessageEntity */
        $messages = MessageEntity::findAll(['recipient_id' => Yii::$app->request->queryParams['recipient_id']]);
        if (!$messages) {
            throw new NotFoundHttpException('Чат с пользователем не найден');
        }
        MessageEntity::deleteAll([
            'created_by'   => Yii::$app->user->identity->getId(),
            'recipient_id' => Yii::$app->request->queryParams['recipient_id']
        ]);

        Yii::$app->getResponse()->setStatusCode(200, 'OK');
        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => 'Чат с пользователем успешно удалён'
        ];
    }
}
