<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;

/**
 * Class BanShopAction
 *
 * @package backend\modules\manage\users\controllers\actions\shop
 */
class BanShopAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'ban-shop';
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
                Yii::$app->getSession()->setFlash('success', "Магазин успешно разбанен.");
                return $this->controller->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('success', "Магазин успешно забанен.");
                return $this->controller->redirect('index');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при изменении статуса магазина!");
            return $this->controller->redirect('index');
        }
    }
}