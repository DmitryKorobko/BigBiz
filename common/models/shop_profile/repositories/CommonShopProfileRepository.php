<?php
namespace common\models\shop_profile\repositories;

use common\models\shop_profile\ShopProfileEntity;

/**
 * Class CommonShopProfileRepository
 *
 * @package common\models\shop_profile\repositories
 */
trait CommonShopProfileRepository
{
    /**
     * Method for create empty shop profile after registration
     *
     * @param $userId
     * @param $name
     * @return bool
     */
    public function createShopProfile($userId, $name): bool
    {
        $profile = new ShopProfileEntity();
        $profile->setScenario(ShopProfileEntity::SCENARIO_CREATE);
        $profile->setAttributes([
            'user_id' => $userId,
            'name'    => $name
        ]);

        return $profile->save(false);
    }
}