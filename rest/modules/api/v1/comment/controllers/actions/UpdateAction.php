<?php

namespace rest\modules\api\v1\comment\controllers\actions;

use common\behaviors\{
    AccessUserStatusBehavior, ValidatePostParameters, ValidationExceptionFirstMessage
};
use common\models\comment\CommentEntity;
use common\models\comment_image\CommentImageEntity;
use yii\rest\Action;
use Yii;
use yii\web\{
    BadRequestHttpException, NotFoundHttpException, ServerErrorHttpException, HttpException
};
use yii\base\{
    ErrorHandler, Exception
};
use yii\db\Exception as ExceptionDb;

/**
 * Class UpdateAction
 *
 * @mixin AccessUserStatusBehavior
 * @mixin ValidatePostParameters
 * @mixin ValidationExceptionFirstMessage
 * @package rest\modules\api\v1\comment\controllers\actions
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
                'class' => ValidatePostParameters::className(),
                'inputParams' => [
                    'comment_id', 'theme_id', 'text'
                ],
            ],
            [
                'class' => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /** Method of update comment
     *
     * @return CommentEntity
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run()
    {
        $postData = Yii::$app->request->post();
        $postData['created_by'] = Yii::$app->user->identity->getId();

        /** @var  $comment CommentEntity */
        $comment = CommentEntity::findOne(['id' => $postData['comment_id'], 'created_by' => $postData['created_by']]);
        if (!$comment) {
            throw new NotFoundHttpException('Комментарий не найден.');
        }
        if (!$comment->acceptCommentUpdate($comment->created_at)) {
            throw new BadRequestHttpException('Обновление комментария запрещено спустя час.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $comment->setScenario(CommentEntity::SCENARIO_UPDATE);
            $comment->load($postData, '');
            if ($comment->save()) {
                if (isset($postData['images']['create'])) {
                    $commentImageModel = new CommentImageEntity();
                    $commentImageModel->saveCommentImages($postData['images']['create'], $comment->id);
                }
                if (isset($postData['images']['delete'])) { 
                    $commentImageModel = new CommentImageEntity();
                    $commentImageModel->deleteCommentImages($postData['images']['delete'], $comment->id);
                    $commentImageModel->deleteImagesFromS3($postData['images']['delete']);
                }
                
                $transaction->commit();
                
                return $comment->getUpdatedCommentDetail($postData);
            }
            ValidationExceptionFirstMessage::throwModelException($comment->errors);
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при обновлении комментария.');
        }
        throw new ServerErrorHttpException('Произошла ошибка при обновлении комментария.');
    }
}