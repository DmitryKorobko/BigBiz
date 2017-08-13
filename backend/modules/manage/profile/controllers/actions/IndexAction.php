<?php
namespace backend\modules\manage\profile\controllers\actions;

use backend\models\BackendUserEntity;
use Yii;
use yii\{
    base\Action, widgets\ActiveForm
};
use common\models\admin_contact\AdminContactEntity;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\profile\controllers\actions
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/profile/views/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * Getting data from AdminContactEntity model. Also implements ajax for
     * no-refresh validation and create/update functions
     *
     * @return string
     */
    public function run()
    {
        /* @var $user BackendUserEntity */
        $user = BackendUserEntity::findIdentity(@Yii::$app->user->id);

        /* @var $modelProfile AdminContactEntity */
        $modelProfile = AdminContactEntity::getProfile();

        if ($modelProfile->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($modelProfile);
            }

            if ($modelProfile->validate() && $modelProfile->save()) {
                Yii::$app->getSession()->setFlash('success', "Данные успешно обновлены!");
            }
        }

        return $this->controller->render($this->view, [
            'modelProfile'         => $modelProfile,
            'user'                 => $user
        ]);
    }
}