<?php
namespace backend\modules\manage\content\controllers;

use yii\{
    web\Controller, filters\AccessControl, web\NotFoundHttpException
};
use backend\modules\manage\content\controllers\actions\theme\{
    CreateAction, DeleteAction, IndexAction, UpdateAction, ViewAction, ChangeThemeStatusAction
};
use common\models\{
    feedback\Feedback, message\MessageEntity, theme\ThemeEntity
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class ThemeController
 *
 * @package backend\modules\manage\content\controllers
 */
class ThemeController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'change-theme-status'],
                        'allow'   => true,
                        'roles'   => ['admin']
                    ]
                ],
                'denyCallback' => function () {
                    if (Yii::$app->user->getIsGuest()) {
                        $this->redirect(Yii::$app->request->baseUrl);
                    }
                }
            ],
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
        $actions['change-theme-status'] = [
            'class' => ChangeThemeStatusAction::class,
        ];

        return $actions;
    }

    /**
     * Find Theme model by ID.
     * @param integer $id
     * @return ThemeEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ThemeEntity::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
