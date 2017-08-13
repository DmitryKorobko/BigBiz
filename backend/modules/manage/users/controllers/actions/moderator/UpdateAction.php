<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;

/**
 * Class UpdateAction
 *
 * @package backend\modules\manage\users\controllers\actions\moderator
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/manage/users/views/moderator/update';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update';
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function run($id)
    {
        /** @var  $model BackendUserEntity */
        $model = $this->controller->findModel($id);
        $model->scenario = BackendUserEntity::SCENARIO_UPDATE;
        $model->setRole(Yii::$app->authManager->getRolesByUser($model->getId()));
        $userId = $model->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);

            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success', "Модератор `{$model->email}` обновлен успешно!");
                return $this->controller->redirect('view?id=' . $userId);
            }
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка при обновлении модератора - {$model->email}!");
            return $this->controller->redirect('view?id=' . $userId);
        }
        return $this->controller->render($this->view, ['model' => $model]);
    }
}