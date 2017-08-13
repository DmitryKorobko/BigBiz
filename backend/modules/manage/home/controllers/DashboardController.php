<?php
namespace backend\modules\manage\home\controllers;

use backend\modules\manage\home\controllers\actions\{
    IndexAction, UpdateStatusOnlineAction
};
use common\models\{
    feedback\Feedback, message\MessageEntity, theme\ThemeEntity
};
use yii\{
    filters\AccessControl, web\Controller
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class DashboardController
 *
 * @package backend\modules\manage\home\controllers
 */
class DashboardController extends Controller
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
                        'actions' => ['index', 'update-status-online'],
                        'allow'   => true,
                        'roles'   => ['admin']
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
        $actions['index'] = [
            'class' => IndexAction::class,
        ];
        $actions['update-status-online'] = [
            'class' => UpdateStatusOnlineAction::class,
        ];

        return $actions;
    }
}
