<?php

namespace rest\modules\api\v1\comment\controllers\actions;

use common\behaviors\{ValidatePostParameters, AccessUserStatusBehavior};
use common\models\{comment\CommentEntity, comment_like\CommentLikeEntity};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class LikeAction Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class LikeAction extends Action
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
                'inputParams' => ['like', 'comment_id']
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
     * Action of like comment
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['user_id'] = Yii::$app->user->identity->getId();

        $comment = CommentEntity::findOne(['id' => $postData['comment_id']]);
        if (!$comment) {
            throw new NotFoundHttpException('Комментарий не найден');
        }

        /** @var  $commentLikeModel CommentLikeEntity.php */
        $commentLikeModel = new CommentLikeEntity();
        $commentLikeModel->scenario = CommentLikeEntity::SCENARIO_CREATE;

        return $commentLikeModel->likeCommentByUser($postData['comment_id'], $postData['like']);


    }
}
