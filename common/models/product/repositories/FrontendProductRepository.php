<?php
namespace common\models\product\repositories;

use common\models\product\ProductEntity;

/**
 * Class FrontendProductRepository
 *
 * @package common\models\product\repositories
 */
trait FrontendProductRepository
{
    /**
     * Method of getting count of all products
     *
     * @return int
     */
    public function getCountAllProducts(): int
    {
        return ProductEntity::find()->count();
    }
}