<?php
namespace backend\modules\shop\home\controllers\actions;

use backend\models\BackendUserEntity;
use common\models\{
    admin_contact\AdminContactEntity, comment\CommentEntity, product\ProductEntity, theme\ThemeEntity,
    shop_feedback\ShopFeedbackEntity, shop_profile\ShopProfileEntity
};
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\shop\home\controllers\actions
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/shop/home/views/home/index';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function run():string
    {
        /** @var  $contact AdminContactEntity.php */
        $contact = AdminContactEntity::find()->where(['is_boss' => 1])->one();
        /** @var  $user  BackendUserEntity.php */
        $user = BackendUserEntity::findOne(['id' => \Yii::$app->user->identity->getId()]);
        /** @var  $comment CommentEntity.php */
        $comment = new CommentEntity();
        /** @var  $product ProductEntity.php */
        $product = new ProductEntity();
        /** @var  $feedback ShopFeedbackEntity.php */
        $feedback = new ShopFeedbackEntity();
        /** @var  $shopProfile ShopProfileEntity.php */
        $shopProfile = ShopProfileEntity::getProfile();

        $accountDaysLeft = round(($shopProfile->category_end - time())/86400);
        $notActive = (($shopProfile->category_end - time()) > 0) ? false : true;

        return $this->controller->render($this->view, [
            'contact'                => $contact,
            'count_products'         => ProductEntity::find()->where(['user_id' => $user->id])->count(),
            'count_themes'           => ThemeEntity::find()->where(['user_id' => $user->id])->count(),
            'count_comments'         => $comment->getTotalCountCommentByShop($user->id),
            'count_product_feedback' => $product->getTotalCountProductFeedback($user->id),
            'most_popular_products'  => $product->getMostPopularProducts(),
            'count_of_shop_reviews'  => $feedback->getCountShopReviews(),
            'account_days_left'      => ($accountDaysLeft > 0) ? 'Осталось дней: ' . $accountDaysLeft
                : 'Аккаунт не активен','not_active'             => $notActive
        ]);
    }
}