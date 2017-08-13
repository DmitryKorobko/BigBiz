<?php
namespace backend\modules\manage\users\controllers\actions\customer;

use backend\modules\manage\users\models\CustomerSearch;
use common\models\user\UserEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\users\controllers\actions\customer
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/users/views/customer/index';

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
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, UserEntity::ROLE_USER);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}