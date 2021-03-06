<?php
namespace backend\modules\shop\control\controllers\actions\theme;

use yii\base\Action;
use common\models\theme\ThemeEntity;
use Yii;

/**
 * Class ViewAction
 *
 * @package backend\modules\shop\control\controllers\actions\theme
 */
class ViewAction extends Action
{
    public $view = '@backend/modules/shop/control/views/theme/view';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'view';
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
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');

        return $this->controller->render($this->view, [
            'theme'                => $theme,
            'userId'               => $userId
        ]);
    }
}