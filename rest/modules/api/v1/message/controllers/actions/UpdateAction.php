<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\{
    behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior,
    models\message\MessageEntity, models\message_image\MessageImageEntity
};
use Yii;
use yii\{
    rest\Action, web\BadRequestHttpException, web\HttpException, web\NotFoundHttpException, web\ServerErrorHttpException
};
use yii\base\{
    ErrorHandler, Exception
};
use yii\db\Exception as ExceptionDb;

/**
 * Class Update Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\message\controllers\actions
 */
class UpdateAction extends Action
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
                'inputParams' => ['text']
            ],
            [
                'class' => AccessUserStatusBehavior::className(),
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
     * Action of updating message by user
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run($id): array
    {
        /** @var  $model MessageEntity */
        $message = MessageEntity::findOne(['id' => $id, 'created_by' => Yii::$app->user->identity->getId()]);
        if (!$message) {
            throw new NotFoundHttpException('Сообщение не найдено.');
        }
        if (!$message->acceptMessageUpdate($message->created_at)) {
            throw new BadRequestHttpException('Обновление сообщения запрещено спустя час.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $postData = Yii::$app->getRequest()->getBodyParams();

            $message->scenario = MessageEntity::SCENARIO_UPDATE;
            $message->load($postData, '');
            if ($message->save()) {
                if (isset($postData['images']['create'])) {
                    $messageImage = new MessageImageEntity();
                    $messageImage->saveMessageImages($postData['images']['create'], $message->id);
                }
                if (isset($postData['images']['delete'])) {
                    $messageImage = new MessageImageEntity();
                    $messageImage->deleteMessageImages($postData['images']['delete'], $id);
                    $messageImage->deleteImagesFromS3($postData['images']['delete']);
                }
                $transaction->commit();

                Yii::$app->response->setStatusCode(200, 'OK');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Сообщение успешно изменено',
                    'data'    => $message->getUpdatedMessageDetail($id, $postData)
                ];
            }
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при обновлении сообщения.');
        }
    }
}
