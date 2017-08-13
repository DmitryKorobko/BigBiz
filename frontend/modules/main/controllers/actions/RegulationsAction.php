<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;

/**
 * Class RegulationsAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class RegulationsAction extends Action
{
    public $view = '@frontend/modules/main/views/regulations';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'regulations';
    }

    /**
     * @return string
     */
    public function run():string
    {
        return $this->controller->render($this->view);
    }
}