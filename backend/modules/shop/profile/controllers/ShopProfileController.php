<?php
namespace backend\modules\shop\profile\controllers;

use backend\modules\shop\profile\controllers\actions\{
    IndexAction, SendVerificationCodeAction, UpdateConfidentialityAction, UpdateNotificationsSettingsAction,
    UpdatePasswordAction, UpdateStatusOnlineAction, VerifyAction
};
use yii\{
    filters\AccessControl, web\Controller
};
use common\models\{
    message\MessageEntity, answer\AnswerEntity, shop_feedback\ShopFeedbackEntity, shop_profile\ShopProfileEntity
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class ShopProfileController
 *
 * @package backend\modules\shop\profile\controllers
 */
class ShopProfileController extends Controller
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
                        'actions' => [
                            'index',
                            'update-password',
                            'verify',
                            'send-verification-code',
                            'update-notifications-settings',
                            'update-confidentiality',
                            'update-status-online'
                        ],
                        'allow'   => true,
                        'roles'   => ['shop']
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
            /** @var  $answer AnswerEntity */
            $answer = new AnswerEntity();
            /** @var  $review ShopFeedbackEntity */
            $review = new ShopFeedbackEntity();
            /** @var  $shopProfile ShopProfileEntity */
            $shopProfile = ShopProfileEntity::getProfile();
            $this->view->params = [
                'user_created_at' => $user->created_at,
                'newMessages' => $message->getCountNewMessagesByCurrentUser(),
                'messages' => $message->getListRecipientChats(MessageEntity::STATUS_MESSAGE_UNREAD),
                'newAnswers' => $answer->getCountNewAnswers(),
                'answers' => $answer->getListAnswersByType(AnswerEntity::STATUS_UNREAD),
                'newReviews' => $review->getCountNewReviews(),
                'reviews' => $review->getListShopReviews(ShopFeedbackEntity::STATUS_UNREAD),
                'status' => BackendUserEntity::findIdentity(Yii::$app->user->id)->status,
                'statusOnline' => BackendUserEntity::findIdentity(Yii::$app->user->id)->status_online,
                'categoryStart' => $shopProfile->category_start,
                'categoryEnd' => $shopProfile->category_end
            ];
        }

        $actions = parent::actions();

        $actions['error'] = [
            'class' => 'yii\web\ErrorAction',
        ];

        $actions['index'] = [
            'class' => IndexAction::class,
        ];

        $actions['update-password'] = [
            'class' => UpdatePasswordAction::class,
        ];

        $actions['verify'] = [
            'class' => VerifyAction::class,
        ];

        $actions['send-verification-code'] = [
            'class' => SendVerificationCodeAction::class,
        ];

        $actions['update-notifications-settings'] = [
            'class' => UpdateNotificationsSettingsAction::class,
        ];

        $actions['update-confidentiality'] = [
            'class' => UpdateConfidentialityAction::class,
        ];

        $actions['update-status-online'] = [
            'class' => UpdateStatusOnlineAction::class,
        ];

        return $actions;
    }
}
