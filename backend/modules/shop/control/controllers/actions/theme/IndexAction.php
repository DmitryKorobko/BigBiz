<?php
namespace backend\modules\shop\control\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\Action;


/**
 * Class IndexAction
 *
 * @package backend\modules\shop\control\controllers\actions\theme
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/shop/control/views/theme/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function run()
    {
        $searchModel = new ThemeEntity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}