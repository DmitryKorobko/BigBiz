<?php

namespace rest\modules\api\v1\content\controllers\actions\banner;

use yii\rest\Action;
use common\models\mobile_banner\MobileBannerEntity;

/**
 * Class ListBanner Action
 *
 * @package rest\modules\api\v1\content\controllers\actions\banner
 */
class ListBannerAction extends Action
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function run(): array
    {
        /** @var  $bannerModel MobileBannerEntity.php */
        $bannerModel = new $this->modelClass;

        return $bannerModel->getListBanners();
    }
}
