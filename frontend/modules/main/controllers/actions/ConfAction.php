<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;

/**
 * Class ConfAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class ConfAction extends Action
{
    public $view = '@frontend/modules/main/views/conf';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'conf';
    }

    /**
     * @return string
     */
    public function run():string
    {
        return $this->controller->render($this->view);
    }
}