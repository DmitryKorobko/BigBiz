<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use Yii;
use yii\base\Action;
use common\models\user\UserEntity;

/**
 * Class DeleteAction
 *
 * @package backend\modules\manage\users\controllers\actions\moderator
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
     * Deletes an existing moder user.
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

        if ($user->save(false)) {
            Yii::$app->getSession()->setFlash('success', "Модератор `{$user->email}` удалён успешно!");
            return $this->controller->redirect('index');
        }

        Yii::$app->getSession()->setFlash('error',
            "Произошла ошибка при удалении модератора - {$user->email}!");
        return $this->controller->redirect('index');
    }
}