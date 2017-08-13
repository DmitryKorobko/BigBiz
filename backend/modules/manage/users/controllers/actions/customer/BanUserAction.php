<?php
namespace backend\modules\manage\users\controllers\actions\customer;

use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;

/**
 * Class BanUserAction
 *
 * @package backend\modules\manage\users\controllers\actions\customer
 */
class BanUserAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'ban-user';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var  $user BackendUserEntity.php */
        $user = BackendUserEntity::findOne(['id' => $id]);
        $status = ($user->status === BackendUserEntity::STATUS_BANNED) ? BackendUserEntity::STATUS_VERIFIED
            : BackendUserEntity::STATUS_BANNED;
        if ($user->setStatus($status)) {
            if ($status === BackendUserEntity::STATUS_VERIFIED) {
                Yii::$app->getSession()->setFlash('success', "Пользователь успешно разбанен.");
                return $this->controller->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('success', "Пользователь успешно забанен.");
                return $this->controller->redirect('index');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла при изменении статуса пользователя!");
            return $this->controller->redirect('index');
        }
    }
}