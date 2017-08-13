<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use common\models\comment\CommentEntity;
use common\models\feedback\Feedback;
use common\models\mobile_banner\MobileBannerEntity;
use common\models\product\ProductEntity;
use common\models\shop_feedback\ShopFeedbackEntity;
use common\models\shop_profile\ShopProfileEntity;
use common\models\theme\ThemeEntity;
use common\models\website_banner\WebsiteBannerEntity;
use Yii;
use yii\base\Action;

/**
 * Class ViewAction
 *
 * @package backend\modules\manage\administrator\controllers\actions\shop
 */
class ViewAction extends Action
{
    public $view = '@backend/modules/manage/users/views/shop/view';

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

        /** @var  $modelProduct ProductEntity.php */
        $modelProduct = new ProductEntity();
        $productParams = ['shop_id' => $id, 'limit' => 5];
        if (isset(Yii::$app->request->queryParams['ProductEntity'])) {
            $productParams = array_merge($productParams, Yii::$app->request->queryParams);
        }
        $dataProviderProduct = $modelProduct->search($productParams);

        /** @var  $websiteBanner WebsiteBannerEntity.php */
        $websiteBanner = new WebsiteBannerEntity();
        $dataProviderWebsiteBanner = $websiteBanner->search(['shop_id' => $id, 'limit' => 5]);

        /** @var  $mobileBanner MobileBannerEntity.php */
        $mobileBanner = new MobileBannerEntity();
        $dataProviderMobileBanner = $mobileBanner->search(['shop_id' => $id, 'limit' => 5]);

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

        /* @var $modelProfile ShopProfileEntity */
        if ($modelProfile = ShopProfileEntity::find()->where(['user_id' => $id])->one()) {
            if ($modelProfile->category_end && $modelProfile->category_start) {
                $modelProfile->_categoryStart = Yii::$app->formatter->asDate($modelProfile->category_start, 'dd.MM.yyyy');
                $modelProfile->_categoryEnd = Yii::$app->formatter->asDate($modelProfile->category_end, 'dd.MM.yyyy');
            }
        } else {
            $modelProfile = new ShopProfileEntity();
        }

        if ($modelProfile->load(Yii::$app->request->post()) && $modelProfile->validate()) {
            $modelProfile->category_start = strtotime(Yii::$app->request->post('ShopProfileEntity')['_categoryStart']);
            $modelProfile->category_end = strtotime(Yii::$app->request->post('ShopProfileEntity')['_categoryEnd']);
            if ($modelProfile->save()) {
                Yii::$app->getSession()->setFlash('success',
                    "Магазину {$modelProfile->name} успешно продлен аккаунт!");

                return $this->controller->redirect('index');
            }
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка при продлении аккаунта для магазина - {$modelProfile->name}!");

            return $this->controller->redirect('index');
        }

        return $this->controller->render($this->view, [
            'rating'                    => $shopFeedback->getAverageShopRating($id),
            'shopFeedbackProvider'      => $shopFeedback->getListFeedBackByUserId(Yii::$app->request->queryParams, $id),
            'comment'                   => $comment,
            'shopFeedback'              => $shopFeedback,
            'modelTheme'                => $modelTheme,
            'modelProduct'              => $modelProduct,
            'profile'                   => $modelProfile,
            'dataProviderTheme'         => $dataProviderTheme,
            'dataProviderProduct'       => $dataProviderProduct,
            'dataProviderWebsiteBanner' => $dataProviderWebsiteBanner,
            'dataProviderMobileBanner'  => $dataProviderMobileBanner,
            'dataProviderComments'      => $dataProviderComments,
            'dataProviderFeedback'      => $dataProviderFeedback
        ]);
    }
}