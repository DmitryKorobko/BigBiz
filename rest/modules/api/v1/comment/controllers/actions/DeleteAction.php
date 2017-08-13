<?php

namespace rest\modules\api\v1\comment\controllers\actions;

use common\behaviors\AccessUserStatusBehavior;
use common\models\comment\CommentEntity;
use common\models\comment_image\CommentImageEntity;
use yii\rest\Action;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException, HttpException
};
use Yii;
use yii\base\{
    ErrorHandler, Exception
};
use yii\db\Exception as ExceptionDb;

/**
 * Class DeleteAction
 * 
 * @mixin AccessUserStatusBehavior
 * @package rest\modules\api\v1\comment\controllers\actions
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

    /** Method of delete comment
     *
     * @return array
     * @param id
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws HttpException
     */
    public function run($id): array
    {
        /** @var  $comment  CommentEntity*/
        $comment = CommentEntity::findOne(['id' => $id, 'created_by' => Yii::$app->user->identity->getId()]);
        if (!$comment) {
            throw new NotFoundHttpException('Комментарий не найден.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $commentImages = $comment->getImages()->select(['src'])->all();

            /** @var  $commentImageModel CommentImageEntity */
            $commentImageModel = new CommentImageEntity();
            $commentImageModel->deleteImagesFromS3($commentImages);

            if ($comment->delete()) {
                $transaction->commit();

                Yii::$app->response->setStatusCode(200, 'OK');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Комментарий успешно удалён',
                    'data'    => ['comment_id' => (int) $id]
                ];
            }
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при удалении комментария.');
        }
        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке
            администарации приложения.');
    }
}