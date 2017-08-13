<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use common\models\website_banner\WebsiteBannerEntity;
use Yii;
use yii\base\Action;

/**
 * Class TurnOnWebsiteBannerAction
 *
 * @package backend\modules\manage\users\controllers\actions\shop
 */
class TurnOnWebsiteBannerAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'turn-on-website-banner';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        $banner = WebsiteBannerEntity::findOne(['id' => $id]);
        $userId = $banner->user_id;
        if ($banner->setStatus()) {
            if ($banner->status) {
                Yii::$app->getSession()->setFlash('success', "Баннер для сайта включен.");
                return $this->controller->redirect('view?id=' . $userId);
            } else {
                Yii::$app->getSession()->setFlash('success', "Баннер для сайта выключен.");
                return $this->controller->redirect('view?id=' . $userId);
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла при изменении статуса баннера!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}