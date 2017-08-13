<?php
namespace backend\modules\authorization\controllers\actions;

use yii\base\Action;

/**
 * Class ErrorAction
 *
 * @package backend\modules\authorization\controllers\actions
 */
class ErrorAction extends Action
{
    public $view = '@backend/modules/authorization/views/authorization/error';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'error';
    }

    public function run()
    {
        $this->controller->render($this->view);
    }
}