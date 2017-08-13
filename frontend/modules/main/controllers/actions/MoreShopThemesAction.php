<?php
namespace frontend\modules\main\controllers\actions;

use yii\{
    base\Action, helpers\BaseJson
};
use common\models\theme\ThemeEntity;
use Yii;
use stdClass;

/**
 * Class MoreShopThemesAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class MoreShopThemesAction extends Action
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
        return 'more-shop-themes';
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var  $theme ThemeEntity*/
        $theme = new ThemeEntity();
        $shop  = Yii::$app->request->post()['shop'];
        $limit = Yii::$app->request->post()['limit'];

        $result = new stdClass();
        $result->limit = $limit + Yii::$app->params['themesPerPage'];
        $result->themes = $theme->getListThemesByCategoryOrShop(null, $shop, true, $limit,
            true);

        return BaseJson::encode($result);
    }
}