<?php
namespace backend\modules\manage\users\controllers\actions\administrator;

use backend\models\BackendUserEntity;
use common\models\user\UserEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\users\controllers\actions\administrator
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/users/views/administrator/index';

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
        $searchModel = new BackendUserEntity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, UserEntity::ROLE_ADMIN);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}