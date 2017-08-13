<?php
namespace backend\modules\shop\control\controllers;

use backend\modules\shop\control\controllers\actions\product\{
    CreateAction, DeleteAction, IndexAction, UpdateAction, SetAvailabilityAction, ViewAction
};
use yii\{
    filters\AccessControl, web\Controller, web\NotFoundHttpException, web\ForbiddenHttpException
};
use common\models\{
    message\MessageEntity, answer\AnswerEntity, shop_feedback\ShopFeedbackEntity, city\CityEntity,
    product\ProductEntity, shop_profile\ShopProfileEntity
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class ProductController
 *
 * @package backend\modules\shop\control\controllers
 */
class ProductController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'set-availability', 'view'],
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
        $actions['set-availability'] = [
            'class' => SetAvailabilityAction::class,
        ];
        $actions['update'] = [
            'class' => UpdateAction::class,
        ];
        $actions['view'] = [
            'class' => ViewAction::class,
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

    /**
     * Find Product model by ID.
     * @param integer $id
     * @return ProductEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ProductEntity::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Method of validation few models
     *
     * @param ProductEntity $modelProduct
     * @param array $modelPrices
     * @param CityEntity $modelCity
     * @return bool
     */
    public function validateModels(ProductEntity $modelProduct, array $modelPrices, CityEntity $modelCity)
    {
        $valid = $modelProduct->validate();
        $valid = $modelCity->validate() && $valid;
        $valid = $modelProduct->validateMultiple($modelPrices) && $valid;

        return $valid;
    }
}