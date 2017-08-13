<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use backend\models\BackendUserEntity;
use common\models\user\UserEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\users\controllers\actions\moderator
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/users/views/moderator/index';

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, UserEntity::ROLE_MODER);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}