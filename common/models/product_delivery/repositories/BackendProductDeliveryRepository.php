<?php
namespace common\models\product_delivery\repositories;

use yii\data\ActiveDataProvider;
use common\models\product_delivery\ProductDeliveryEntity;
use Yii;

/**
 * Class BackendProductDeliveryRepository
 * @package common\models\product_delivery\repositories
 */
trait BackendProductDeliveryRepository
{
    /**
    * Method of getting list product-delivery with filters. Using for GridView
    *
    * @param $params
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = ProductDeliveryEntity::find()
            ->select(['product_delivery.*', 'product.name as name'])
            ->leftJoin('product', 'product.id = product_delivery.product_id')
            ->orderBy('sort asc');

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $params['limit'] ?? 10
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name' => [
                    'asc'     => ['name' => SORT_ASC],
                    'desc'    => ['name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'created_at'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}