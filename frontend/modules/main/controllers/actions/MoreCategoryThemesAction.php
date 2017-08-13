<?php
namespace frontend\modules\main\controllers\actions;

use yii\{
    base\Action, helpers\BaseJson
};
use common\models\theme\ThemeEntity;
use Yii;
use stdClass;

/**
 * Class MoreCategoryThemesAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class MoreCategoryThemesAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'more-category-themes';
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var  $theme ThemeEntity*/
        $theme = new ThemeEntity();
        $category = Yii::$app->request->post()['category'];
        $limit = Yii::$app->request->post()['limit'];

        $result = new stdClass();
        $result->limit = $limit + Yii::$app->params['themesPerPage'];
        $result->themes = $theme->getListThemesByCategoryOrShop($category, null, true, $limit,
            true);

        return BaseJson::encode($result);
    }
}