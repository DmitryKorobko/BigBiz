<?php
namespace backend\modules\moderator\content\controllers;

use yii\{
    web\Controller, filters\AccessControl
};
use backend\modules\moderator\content\controllers\actions\main_category_section\{
    CreateAction, DeleteAction, IndexAction, UpdateAction
};
use common\models\answer\AnswerEntity;
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class MainCategorySectionController
 *
 * @package backend\modules\moderator\content\controllers
 */
class MainCategorySectionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['moder']
                    ]
                ],
                'denyCallback' => function () {
                    if (Yii::$app->user->getIsGuest()) {
                        $this->redirect(Yii::$app->request->baseUrl);
                    }
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        if (!Yii::$app->user->getIsGuest()) {
            /** @var  $user BackendUserEntity */
            $user = BackendUserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);
            /** @var  $answer AnswerEntity */
            $answer = new AnswerEntity();
            $this->view->params = [
                'user_created_at' => $user->created_at,
                'newAnswers' => $answer->getCountNewCommentReplies(),
                'answers' => $answer->getListAnswersByType(AnswerEntity::STATUS_UNREAD,
                    true, AnswerEntity::TYPE_REPLY_COMMENT),
                'status' => BackendUserEntity::findIdentity(Yii::$app->user->id)->status,
                'statusOnline' => BackendUserEntity::findIdentity(Yii::$app->user->id)->status_online
            ];
        }

        $actions = parent::actions();

        $actions['error'] = [
            'class' => 'yii\web\ErrorAction',
        ];
        $actions['create'] = [
            'class' => CreateAction::class,
        ];
        $actions['delete'] = [
            'class' => DeleteAction::class,
        ];
        $actions['index'] = [
            'class' => IndexAction::class,
        ];
        $actions['update'] = [
            'class' => UpdateAction::class,
        ];

        return $actions;
    }
}

