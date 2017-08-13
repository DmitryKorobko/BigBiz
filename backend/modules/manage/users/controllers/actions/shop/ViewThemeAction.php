<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use yii\base\Action;
use common\models\theme\ThemeEntity;
use Yii;

/**
 * Class ViewThemeAction
 *
 * @package backend\modules\manage\administrator\controllers\actions\shop
 */
class ViewThemeAction extends Action
{
    public $view = '@backend/modules/manage/users/views/shop/view_theme';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'view-theme';
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function run($id):string
    {
        /** @var  $theme ThemeEntity*/
        $theme = ThemeEntity::findOne(['id' => $id]);

        return $this->controller->render($this->view, [
            'theme'                => $theme
        ]);
    }
}