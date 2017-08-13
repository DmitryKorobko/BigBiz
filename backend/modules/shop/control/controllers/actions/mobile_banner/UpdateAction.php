<?php
namespace backend\modules\shop\control\controllers\actions\mobile_banner;

use common\models\mobile_banner\MobileBannerEntity;
use common\models\website_banner\WebsiteBannerEntity;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
 * Class UpdateAction
 *
 * @package backend\modules\shop\control\controllers\actions\website_banner
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/shop/control/views/mobile_banner/update';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update';
    }

    /**
     * Action for updating product
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function run($id)
    {
        $banner = MobileBannerEntity::findOne($id);
        $banner->end_date = date('Y-m-d', $banner->end_date);
        $banner->start_date = date('Y-m-d', $banner->start_date);

        $banner->scenario = MobileBannerEntity::SCENARIO_UPDATE;
        $postData = Yii::$app->request->post();
        if ($banner->load($postData) && $banner->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($banner->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', "Баннер обновлен успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка при изменении баннера!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [ 'banner' => $banner ]);
    }
}