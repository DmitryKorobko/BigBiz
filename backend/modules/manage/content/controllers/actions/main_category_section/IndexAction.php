<?php
namespace backend\modules\manage\content\controllers\actions\main_category_section;

use common\models\main_category_section\MainCategorySectionEntity;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\content\controllers\actions\main_category_section
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/content/views/main_category_section/index';

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
        $searchModel = new MainCategorySectionEntity();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}