<?php
namespace backend\modules\manage\users\controllers;

use backend\modules\manage\users\controllers\actions\moderator\{
    CreateAction, DeleteAction, IndexAction, UpdateAction, ViewAction, DeleteThemeAction, DeleteCommentAction,
    ViewThemeAction
};
use backend\models\BackendUserEntity;
use yii\{
    filters\AccessControl, web\Controller, web\NotFoundHttpException
};
use common\models\{
    feedback\Feedback, message\MessageEntity, theme\ThemeEntity
};
use Yii;

/**
 * Class ModeratorController
 *
 * @package backend\modules\manage\users\controllers
 */
class ModeratorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access'            => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'delete-theme',
                            'delete-comment',
                            'change-theme-status',
                            'view-theme'
                        ],
                        'allow'   => true,
                        'roles'   => ['admin'],
                    ],
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
            /** @var  $message MessageEntity */
            $message = new MessageEntity();
            /** @var  $feedback Feedback */
            $feedback = new Feedback();
            /** @var  $theme ThemeEntity */
            $theme = new ThemeEntity();
            $this->view->params = [
                'user_created_at'        => $user->created_at,
                'newMessages'            => $message->getCountNewMessagesByCurrentUser(),
                'messages'               => $message->getListRecipientChats(MessageEntity::STATUS_MESSAGE_UNREAD),
                'newFeedbacks'           => $feedback->getCountNewFeedbacks(),
                'feedbacks'              => $feedback->getListFeedbacks(Feedback::STATUS_UNREAD),
                'status'                 => BackendUserEntity::findIdentity(Yii::$app->user->id)->status,
                'statusOnline'           => BackendUserEntity::findIdentity(Yii::$app->user->id)->status_online,
                'newThemesForAdmin'      => $theme->getListCategoryThemes('', true),
                'newThemesForAdminCount' => $theme->getCountNewThemesForAdmin()
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
        $actions['view'] = [
            'class' => ViewAction::class,
        ];
        $actions['delete-theme'] = [
            'class' => DeleteThemeAction::class,
        ];
        $actions['delete-comment'] = [
            'class' => DeleteCommentAction::class,
        ];
        $actions['view-theme'] = [
            'class' => ViewThemeAction::class,
        ];

        return $actions;
    }

    /**
     * Finds the Backend User model.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return BackendUserEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = BackendUserEntity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
