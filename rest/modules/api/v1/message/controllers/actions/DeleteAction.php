<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\models\{
    message\MessageEntity, message_image\MessageImageEntity
};
use yii\rest\Action;
use common\behaviors\AccessUserStatusBehavior;
use Yii;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException, HttpException
};
use yii\base\{
    ErrorHandler, Exception
};
use yii\db\Exception as ExceptionDb;

/**
 * Class DeleteAction
 *
 * @package rest\modules\api\v1\message\controllers\actions
 * @mixin AccessUserStatusBehavior
 */
class DeleteAction extends Action
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
    public function beforeRun(): bool
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /** Method of delete message
     *
     * @return array
     * @param id
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws HttpException
     */
    public function run($id): array
    {
        /** @var  $message  MessageEntity */
        $message = MessageEntity::findOne(['id' => $id, 'created_by' => Yii::$app->user->identity->getId()]);
        if (!$message) {
            throw new NotFoundHttpException('Сообщение не найдено.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $messageImages = $message->getImages()->select(['src'])->all();

            /** @var  $messageImage MessageImageEntity */
            $messageImage = new MessageImageEntity();
            $messageImage->deleteImagesFromS3($messageImages);

            if ($message->delete()) {
                $transaction->commit();

                Yii::$app->response->setStatusCode(200, 'OK');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Сообщение успешно удалёно',
                    'data'    => ['message_id' => (int) $id]
                ];
            }
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при удалении сообщения.');
        }
        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке
            администарации приложения.');
    }
}