<?php
namespace backend\modules\moderator\content\controllers\actions\child_category_section;

use common\models\child_category_section\ChildCategorySectionEntity;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\moderator\category\controllers\actions\child_category_section
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/moderator/content/views/child_category_section/index';

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
        /** @var  $searchModel ChildCategorySectionEntity */
        $searchModel = new ChildCategorySectionEntity();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}