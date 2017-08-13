<?php
namespace common\models\website_banner\repositories;

use common\models\website_banner\WebsiteBannerEntity;

/**
 * Class FrontendWebsiteBannerRepository
 *
 * @package common\models\website_banner\repositories
 */
trait FrontendWebsiteBannerRepository
{
    /**
     * Method of getting list of all active website banners.
     *
     * @return array
     */
    public function getListWebsiteBanners(): array
    {
        $model = WebsiteBannerEntity::find()
            ->select(['website_banner.image', 'shop_profile.image as shop_image'])
            ->leftJoin('shop_profile', 'shop_profile.user_id = website_banner.user_id')
            ->where(['website_banner.status' => 1])
            ->asArray()
            ->all();

        $banners = [];

        foreach ($model as $banner) {
            $banners[] = [
                'image'      => $banner['image'],
                'shop_image' => $banner['shop_image']
            ];
        }

        shuffle($banners);

        return $banners;
    }
}