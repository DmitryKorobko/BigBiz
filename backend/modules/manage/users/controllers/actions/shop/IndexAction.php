<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use backend\modules\manage\users\models\ShopSearch;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\administrator\controllers\actions\shop
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/users/views/shop/index';

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
        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}