<?php

namespace rest\modules\api\v1\support\controllers\actions;

use common\behaviors\{
    ValidatePostParameters, ValidationExceptionFirstMessage
};
use common\models\feedback\Feedback;
use rest\models\RestUser;
use yii\rest\Action;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class SendLetterAction
 *
 * @package rest\modules\api\v1\support\controllers\actions
 * @mixin ValidatePostParameters
 * @mixin ValidationExceptionFirstMessage
 */
class SendLetterAction extends Action
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
                'inputParams' => ['cause_send', 'message']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /** Method of writing a letter in support
     *
     * @throws ServerErrorHttpException
     * @return array
    */
    public function run(): array
    {
        /** @var  $user  RestUser*/
        $user = RestUser::findOne(['id' => Yii::$app->user->identity->getId()]);

        $postData = Yii::$app->request->bodyParams;
        $postData['user_id'] = $user->id;

        /** @var  $feedback Feedback*/
        $feedback = new $this->modelClass;
        $feedback->load($postData, '');
        if ($feedback->save()) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Письмо успешно отправлено',
                'data'    => $feedback->getAttributes()
            ];
        } elseif ($feedback->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($feedback->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке
            администарации приложения.');
    }
}