<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\{
    behaviors\AccessUserStatusBehavior, behaviors\ValidatePostParameters, behaviors\ValidationExceptionFirstMessage,
    models\user_confidentiality\UserConfidentialityEntity, models\shop_confidentiality\ShopConfidentialityEntity
};
use yii\rest\Action;
use Yii;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException
};

/**
 * Class CleaningChatAction
 *
 * @package rest\modules\api\v1\message\controllers\actions
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 */
class CleaningChatAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['frequency_history_cleaning']
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return bool
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /** Method of setting the frequency of cleaning the history of the chat
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @return array
     */
    public function run(): array
    {
        /** @var  $userConfidentiality UserConfidentialityEntity */
        /** @var  $shopConfidentiality ShopConfidentialityEntity */
        $userConfidentiality = UserConfidentialityEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);
        $shopConfidentiality = ShopConfidentialityEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);
        if ($userConfidentiality) {
            $userConfidentiality->scenario = UserConfidentialityEntity::SCENARIO_UPDATE;
            $userConfidentiality->frequency_history_cleaning = Yii::$app->request->getBodyParam('frequency_history_cleaning');
            if ($userConfidentiality->save()) {
                Yii::$app->response->setStatusCode(200);
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Таймер очистки личных сообщений успешно настроен',
                    'data'    => $userConfidentiality->getAttributes()
                ];
            } elseif ($userConfidentiality->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($userConfidentiality->errors);
            }
        } elseif ($shopConfidentiality) {
            $shopConfidentiality->scenario = ShopConfidentialityEntity::SCENARIO_UPDATE;
            $shopConfidentiality->frequency_history_cleaning = Yii::$app->request->getBodyParam('frequency_history_cleaning');
            if ($shopConfidentiality->save()) {
                Yii::$app->response->setStatusCode(200);
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Таймер очистки личных сообщений успешно настроен',
                    'data'    => $shopConfidentiality->getAttributes()
                ];
            } elseif ($shopConfidentiality->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($shopConfidentiality->errors);
            }
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке 
            администарации приложения.');
    }
}