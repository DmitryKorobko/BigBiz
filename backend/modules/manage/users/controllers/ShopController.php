<?php
namespace backend\modules\manage\users\controllers;

use backend\modules\manage\users\controllers\actions\shop\{
    ChangeThemeStatusAction, DeleteCommentAction, DeleteProductAction, DeleteFeedbackAction, TurnOnWebsiteBannerAction,
    DeleteShopFeedbackAction, BanShopAction, IndexAction, ViewAction, TurnOnMobileBannerAction, DeleteThemeAction,
    ViewThemeAction, ViewProductAction
};
use yii\{
    filters\AccessControl, web\Controller
};
use common\models\{
    feedback\Feedback, message\MessageEntity, theme\ThemeEntity
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class ShopController
 *
 * @package backend\modules\manage\users\controllers
 */
class ShopController extends Controller
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
                            'delete-product',
                            'delete-theme',
                            'delete-feedback',
                            'delete-comment',
                            'delete-shop-feedback',
                            'ban-shop',
                            'change-theme-status',
                            'turn-on-mobile-banner',
                            'turn-on-website-banner',
                            'view-theme',
                            'view-product'
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
        $actions['index'] = [
            'class' => IndexAction::class,
        ];
        $actions['view'] = [
            'class' => ViewAction::class,
        ];
        $actions['turn-on-mobile-banner'] = [
            'class' => TurnOnMobileBannerAction::class,
        ];
        $actions['turn-on-website-banner'] = [
            'class' => TurnOnWebsiteBannerAction::class,
        ];
        $actions['delete-theme'] = [
            'class' => DeleteThemeAction::class,
        ];
        $actions['delete-product'] = [
            'class' => DeleteProductAction::class,
        ];
        $actions['delete-comment'] = [
            'class' => DeleteCommentAction::class,
        ];
        $actions['delete-feedback'] = [
            'class' => DeleteFeedbackAction::class,
        ];
        $actions['delete-shop-feedback'] = [
            'class' => DeleteShopFeedbackAction::class,
        ];
        $actions['ban-shop'] = [
            'class' => BanShopAction::class,
        ];
        $actions['change-theme-status'] = [
            'class' => ChangeThemeStatusAction::class
        ];
        $actions['view-theme'] = [
            'class' => ViewThemeAction::class
        ];
        $actions['view-product'] = [
            'class' => ViewProductAction::class
        ];

        return $actions;
    }
}
