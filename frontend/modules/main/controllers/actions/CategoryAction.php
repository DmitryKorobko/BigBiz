<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;
use common\models\{
    child_category_section\ChildCategorySectionEntity, theme\ThemeEntity
};
use yii\data\ArrayDataProvider;

/**
 * Class CategoryAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class CategoryAction extends Action
{
    public $view = '@frontend/modules/main/views/category';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'category';
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function run($id):string
    {
        /** @var  $category ChildCategorySectionEntity*/
        $category = ChildCategorySectionEntity::findOne(['id' => $id]);
        /** @var  $theme ThemeEntity*/
        $theme = new ThemeEntity();
        /** @var  $themeDataProvider ArrayDataProvider*/
        $themeDataProvider = $theme->getListThemesByCategoryOrShop($category['id']);
        $themes = $themeDataProvider->models;

        return $this->controller->render($this->view, [
            'categoryId'        => $category['id'],
            'categoryName'      => $category['name'],
            'themeDataProvider' => $themeDataProvider,
            'themes'            => $themes,
            'dataCount'         => $themeDataProvider->count,
            'allDataCount'      => $themeDataProvider->totalCount
        ]);
    }
}