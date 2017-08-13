<?php
namespace backend\modules\shop\control\controllers\actions\mobile_banner;

use common\models\mobile_banner\MobileBannerEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\shop\control\controllers\actions\website_banner
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/shop/control/views/mobile_banner/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function run()
    {
        /** @var  $searchModel  MobileBannerEntity.php */
        $searchModel = new MobileBannerEntity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}