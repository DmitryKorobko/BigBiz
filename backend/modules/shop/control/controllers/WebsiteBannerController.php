<?php
namespace backend\modules\shop\control\controllers;

use backend\modules\shop\control\controllers\actions\website_banner\{
    CreateAction, DeleteAction, IndexAction, UpdateAction
};
use yii\{
    filters\AccessControl, web\Controller, web\ForbiddenHttpException
};
use common\models\{
    message\MessageEntity, answer\AnswerEntity, shop_feedback\ShopFeedbackEntity, shop_profile\ShopProfileEntity
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class WebsiteBannerController
 *
 * @package backend\modules\shop\control\controllers
 */
class WebsiteBannerController extends Controller
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

    /**
     * beforeAction check user status before pass and throw exception
     * if he's unverified
     *
     * @param \yii\base\Action $action
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if (BackendUserEntity::findStatusById(Yii::$app->user->id) == BackendUserEntity::STATUS_UNVERIFIED) {
            throw new ForbiddenHttpException('Доступ разрешен только верифицированным пользователям.');
        }

        return parent::beforeAction($action);
    }
}