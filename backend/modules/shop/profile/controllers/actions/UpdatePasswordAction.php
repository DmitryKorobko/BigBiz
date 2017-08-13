<?php
namespace backend\modules\shop\profile\controllers\actions;

use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\shop\profile\controllers\actions
 */
class UpdatePasswordAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update-password';
    }

    /**
     * Action for updating password in "settings" tab.
     * Validate both passwords and saving password (or not if validate failed)
     *
     * @return string
     */
    public function run()
    {
        /* @var $model BackendUserEntity */
        $model = BackendUserEntity::findIdentity(@Yii::$app->user->id);
        $model->scenario = BackendUserEntity::SCENARIO_UPDATE;
        $oldPassword = Yii::$app->request->post('BackendUserEntity')['currentPassword'];

        if (Yii::$app->security->validatePassword($oldPassword, $model->password_hash)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', "Пароль успешно изменен!");
            } else {
                Yii::$app->getSession()->setFlash('error', "Ошибка при смене пароля");
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Неверно введен старый пароль");
        }

        return $this->controller->redirect('index');
    }
}