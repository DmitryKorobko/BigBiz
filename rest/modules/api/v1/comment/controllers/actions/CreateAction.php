<?php

namespace rest\modules\api\v1\comment\controllers\actions;

use common\models\{
    comment_image\CommentImageEntity, comment\CommentEntity
};
use Yii;
use yii\base\ErrorHandler;
use yii\base\Exception;
use yii\db\Exception as ExceptionDb;
use yii\rest\Action;
use yii\web\{
    BadRequestHttpException, HttpException, ServerErrorHttpException
};
use common\behaviors\{
    ValidatePostParameters, AccessUserStatusBehavior, ValidationExceptionFirstMessage
};

/**
 * Class Create Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\comment\controllers\actions
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
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['text', 'theme_id']
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Add comment for theme by user
     *
     * @return array|CommentEntity
     * @throws HttpException
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
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
            /* @var $comment CommentEntity.php */
            $comment = new $this->modelClass();
            $comment->scenario = CommentEntity::SCENARIO_CREATE;

            if (!isset($postData['recipient_id'])) {
                $postData['status'] = CommentEntity::STATUS_READ;
            }

            $comment->load($postData, '');
            if ($comment->save()) {
                if (isset($postData['images'])) {
                    $commentImageModel = new CommentImageEntity();
                    $commentImageModel->saveCommentImages($postData['images'], $comment->id);
                }

                $transaction->commit();

                Yii::$app->getResponse()->setStatusCode(201, 'Created');
                return [
                    'status'     => Yii::$app->getResponse()->statusCode,
                    'message'    => 'Комментарий успешно добавлен',
                    'data'       => $comment->getCreatedCommentDetail($comment->id)
                ];
            } elseif ($comment->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($comment->errors);
            }
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при добавлении комментария.');
        }
        throw new ServerErrorHttpException('Произошла ошибка при добавлении комментария.');
    }
}
