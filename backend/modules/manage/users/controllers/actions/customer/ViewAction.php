<?php
namespace backend\modules\manage\users\controllers\actions\customer;

use backend\models\BackendUserEntity;
use common\models\{
    comment\CommentEntity, feedback\Feedback, shop_feedback\ShopFeedbackEntity, user_profile\UserProfileEntity,
    theme\ThemeEntity, product_feedback\ProductFeedbackEntity
};
use yii\base\Action;
use Yii;

/**
 * Class ViewAction
 *
 * @package backend\modules\manage\administrator\controllers\actions\customer
 */
class ViewAction extends Action
{
    public $view = '@backend/modules/manage/users/views/customer/view';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'view';
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function run($id)
    {
        /** @var  $modelTheme ThemeEntity.php */
        $modelTheme = new ThemeEntity();
        $themeParams = ['shop_id' => $id, 'limit' => 10];
        if (isset(Yii::$app->request->queryParams['ThemeEntity'])) {
            $themeParams = array_merge($themeParams, Yii::$app->request->queryParams);
        }
        $dataProviderTheme = $modelTheme->search($themeParams);

        /** @var $feedBack Feedback.php */
        $feedBack = new Feedback();
        $dataProviderFeedback = $feedBack->search(['user_id' => $id]);

        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = new ShopFeedbackEntity();

        /** @var  $comment CommentEntity.php */
        $comment = new CommentEntity();
        $commentParams = ['shop_id' => $id, 'limit' => 10];
        if (isset(Yii::$app->request->queryParams['CommentEntity'])) {
            $commentParams = array_merge($commentParams, Yii::$app->request->queryParams);
        }
        $dataProviderComments = $comment->search($commentParams);

        /* @var $modelProfile UserProfileEntity */
        $modelProfile = UserProfileEntity::find()->where(['user_id' => $id])->one();
        if (!$modelProfile) {
            $modelProfile = new UserProfileEntity();
        }

        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $productFeedback = new ProductFeedbackEntity();

        /** @var $statistics BackendUserEntity.php */
        $statistics = new BackendUserEntity();
        $dataProviderStatistics = $statistics->search(Yii::$app->request->queryParams,
            BackendUserEntity::ROLE_USER);

        return $this->controller->render($this->view, [
            'shopFeedbackProvider'      => $shopFeedback->getListFeedBackByCreatorId($id),
            'comment'                   => $comment,
            'shopFeedback'              => $shopFeedback,
            'modelTheme'                => $modelTheme,
            'profile'                   => $modelProfile,
            'dataProviderTheme'         => $dataProviderTheme,
            'dataProviderComments'      => $dataProviderComments,
            'dataProviderFeedback'      => $dataProviderFeedback,
            'productFeedbackProvider'   => $productFeedback->getListFeedBackByCreatorId($id),
            'productFeedback'           => $productFeedback,
            'dataProviderStatistics'    => $dataProviderStatistics
        ]);
    }
}