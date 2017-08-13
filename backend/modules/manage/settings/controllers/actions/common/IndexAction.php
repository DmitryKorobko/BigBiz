<?php
namespace backend\modules\manage\settings\controllers\actions\common;

use common\models\settings\SettingsEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\settings\controllers\actions\common
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/settings/views/common/index';

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
        $searchModel = new SettingsEntity();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}