<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\{
    behaviors\ValidationExceptionFirstMessage, models\message\MessageEntity, behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior, models\message_image\MessageImageEntity
};
use Yii;
use yii\{
    rest\Action, web\BadRequestHttpException, web\ServerErrorHttpException, web\HttpException
};
use yii\base\ErrorHandler;
use yii\base\Exception;
use yii\db\Exception as ExceptionDb;

/**
 * Class Create Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\message\controllers\actions
 */
class CreateAction extends Action
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
                'inputParams' => ['recipient_id', 'text']
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

    /**
     * Add message by user
     *
     * @return array|\yii\db\ActiveRecord
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run(): array
    {
        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['created_by'] = Yii::$app->user->identity->getId();
        if (isset($postData['images']) && count($postData['images']) > Yii::$app->params['countAttachedImages']) {
            throw new BadRequestHttpException('Количество прикреплённых картинок не должно быть больше 5.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $transaction->commit();

            /* @var $message MessageEntity.php */
            $message = new $this->modelClass();
            $message->scenario = MessageEntity::SCENARIO_CREATE;
            $message->load($postData, ''); 
            if ($message->save()) {
                if (isset($postData['images'])) {
                    /** @var  $messageImage MessageImageEntity */
                    $messageImage = new MessageImageEntity();
                    $messageImage->saveMessageImages($postData['images'], $message->id);
                }
                
                Yii::$app->getResponse()->setStatusCode(200, 'OK');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Сообщение успешно отправлено',
                    'data'    => $message->getCreatedMessageDetail($message->id)
                ];
            } elseif ($message->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($message->errors);
            }
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при добавлении сообщения.');
        }
    }
}
