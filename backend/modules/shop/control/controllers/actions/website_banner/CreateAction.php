<?php
namespace backend\modules\shop\control\controllers\actions\website_banner;

use common\models\website_banner\WebsiteBannerEntity;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\ErrorHandler;

/**
 * Class CreateAction
 *
 * @package backend\modules\shop\control\controllers\actions\website_banner
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/shop/control/views/website_banner/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * Action for creating product
     *
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /* @var  $modelItem WebsiteBannerEntity */
        $banner = new WebsiteBannerEntity();
        $banner->scenario = WebsiteBannerEntity::SCENARIO_CREATE;

        $postData = Yii::$app->request->post();
        if ($postData) {
            $postData['WebsiteBannerEntity']['user_id'] = Yii::$app->user->identity->getId();
        } else {
            /** Default value */
            $banner->start_date = Yii::$app->formatter->asDate(time(), 'php: Y-m-d');
            $banner->end_date = Yii::$app->formatter->asDate(time() + (86400 * 31), 'php: Y-m-d');
            $banner->period_of_time = $banner->start_date . ' - ' . $banner->end_date;
        }

        if ($banner->load($postData) && $banner->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($banner->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success',
                        "Баннер добавлен успешно! Свяжитесь с администрацией.");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::error(ErrorHandler::convertExceptionToString($e));
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка при добавлении баннера!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [
            'banner' => $banner
        ]);
    }
}