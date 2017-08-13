<?php
namespace backend\modules\manage\content\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\Action;


/**
 * Class IndexAction
 *
 * @package backend\modules\manage\content\controllers\actions\theme
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/content/views/theme/index';

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}