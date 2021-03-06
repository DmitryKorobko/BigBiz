<?php
namespace backend\modules\manage\users\controllers\actions\administrator;

use Yii;
use yii\base\Action;
use common\models\user\UserEntity;

/**
 * Class DeleteAction
 *
 * @package backend\modules\manage\users\controllers\actions\administrator
 */
class DeleteAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete';
    }

    /**
     * Deletes an existing admin user.
     *
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function run($id)
    {
        /** @var  $user UserEntity */
        $user = UserEntity::findOne(['id' => $id]);
        $user->scenario = UserEntity::SCENARIO_UPDATE;
        $user->setAttribute('is_deleted', 1);

        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('error',
                "Нельзя удалить самого себя!");
            return $this->controller->redirect('index');
        }

        if ($user->save(false)) {
            Yii::$app->getSession()->setFlash('success', "Администратор `{$user->email}` удалён успешно!");
            return $this->controller->redirect('index');
        }

        Yii::$app->getSession()->setFlash('error',
            "Произошла ошибка при удалении администратора - {$user->email}!");
        return $this->controller->redirect('index');
    }
}