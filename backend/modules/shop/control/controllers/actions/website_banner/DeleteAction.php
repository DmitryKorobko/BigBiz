<?php
namespace backend\modules\shop\control\controllers\actions\website_banner;

use common\models\website_banner\WebsiteBannerEntity;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteAction
 *
 * @package backend\modules\shop\control\controllers\actions\website_banner
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
     * Action for deleting banner with image
     *
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function run($id)
    {
       /** @var $banner WebsiteBannerEntity.php */
        $banner = WebsiteBannerEntity::findOne($id);
        try {
            $banner->delete();
            Yii::$app->getSession()->setFlash('success', "Баннер удален успешно!");
            return $this->controller->redirect(['index']);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка. Попробуйте еще раз или обратитесь в поддержку!");
            return $this->controller->redirect(['index']);
        }
    }
}