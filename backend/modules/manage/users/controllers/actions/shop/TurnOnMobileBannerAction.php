<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use common\models\mobile_banner\MobileBannerEntity;
use Yii;
use yii\base\Action;

/**
 * Class TurnOnMobileBannerAction
 *
 * @package backend\modules\manage\users\controllers\actions\shop
 */
class TurnOnMobileBannerAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'turn-on-mobile-banner';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        $banner = MobileBannerEntity::findOne(['id' => $id]);
        $userId = $banner->user_id;
        if ($banner->setStatus()) {
            if ($banner->status) {
                Yii::$app->getSession()->setFlash('success', "Баннер для мобильного приложения включен.");
                return $this->controller->redirect('view?id=' . $userId);
            } else {
                Yii::$app->getSession()->setFlash('success', "Баннер для мобильного приложени выключен.");
                return $this->controller->redirect('view?id=' . $userId);
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла при изменении статуса баннера!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}