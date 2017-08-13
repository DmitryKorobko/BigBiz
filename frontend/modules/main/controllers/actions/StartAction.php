<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;

/**
 * Class StartAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class StartAction extends Action
{
    public $view = '@frontend/modules/main/views/start';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'start';
    }

    /**
     * @return string
     */
    public function run():string
    {
        return $this->controller->render($this->view);
    }
}