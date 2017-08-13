<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;
use common\models\{
    main_category_section\MainCategorySectionEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity,
    theme\ThemeEntity, product\ProductEntity, website_banner\WebsiteBannerEntity, admin_contact\AdminContactEntity
};
use Yii;

/**
 * Class IndexAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class IndexAction extends Action
{
    public $view = '@frontend/modules/main/views/index';

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
        /** @var  $product ProductEntity*/
        $product = new ProductEntity();
        /** @var  $theme ThemeEntity*/
        $theme = new ThemeEntity();
        /** @var  $user UserProfileEntity*/
        $user = new UserProfileEntity();
        /** @var  $shop ShopProfileEntity*/
        $shop = new ShopProfileEntity();
        $shops = $shop->getListShopsWithMainInformation();
        /** @var  $banner WebsiteBannerEntity*/
        $banner = new WebsiteBannerEntity();
        /** @var  $mainCategories  MainCategorySectionEntity*/
        $mainCategories = new MainCategorySectionEntity();
        $mainCategories = $mainCategories->getListMainCategories();
        $topCategory = empty($mainCategories[0]) ? [] : $mainCategories[0];
        unset($mainCategories[0]);
        /** @var  $contact AdminContactEntity */
        $adminContact = AdminContactEntity::find()->where(['is_boss' => 1])->one();

        return $this->controller->render($this->view, [
            'productsCount'  => $product->getCountAllProducts(),
            'themesCount'    => $theme->getCountAllThemes(),
            'usersCount'     => $user->getCountAllUsers(),
            'shopsCount'     => $shop->getCountAllShops(),
            'topUsers'       => $user->getListTopUsers(),
            'WebSiteBanners' => $banner->getListWebsiteBanners(),
            'shops'          => $shops,
            'topCategory'    => $topCategory,
            'mainCategories' => $mainCategories,
            'adminContact'   => $adminContact,
            'defaultCount'   => Yii::$app->params['shopsPerPage'],
            'allShopsCount'  => count($shops)
        ]);
    }
}