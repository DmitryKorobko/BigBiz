<?php

namespace rest\modules\api\v1\shop\controllers\actions\review;

use common\{
    models\shop_profile\ShopProfileEntity, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    rest\Action, data\ArrayDataProvider, web\NotFoundHttpException
};
/**
 * Class ReviewsAction
 *
 * @mixin ValidateGetParameters
 *
 * @package rest\modules\api\v1\shop\controllers\actions\review
 */
class ReviewsAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['shop_id']
            ],
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting reviews about shop.
     *
     * @return \yii\data\ArrayDataProvider
     * @throws NotFoundHttpException
     */
    public function run(): ArrayDataProvider
    {
        /** @var  $profile ShopProfileEntity.php */
        $profile = ShopProfileEntity::findOne(['user_id' => Yii::$app->request->queryParams['shop_id']]);

        if (!$profile) {
            throw new NotFoundHttpException('Магазин не найден.');
        }

        /** @var  $review ShopProfileEntity.php */
        $review = new $this->modelClass;

        return $review->getShopReviews(Yii::$app->request->queryParams);
    }
}