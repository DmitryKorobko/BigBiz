<?php

namespace rest\modules\api\v1\shop\controllers\actions\review;

use common\{
    models\shop_feedback\ShopFeedbackEntity, models\shop_profile\ShopProfileEntity, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException
};

/**
 * Class Rating Action
 *
 * @mixin ValidateGetParameters
 *
 * @package rest\modules\api\v1\shop\controllers\actions\review
 */
class RatingAction extends Action
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
     * Action of getting full rating shop information

     * @return array
     * @throws NotFoundHttpException
     */
    public function run(): array
    {
        /** @var  $profile ShopProfileEntity.php */
        $profile = ShopProfileEntity::findOne(['user_id' => Yii::$app->request->queryParams['shop_id']]);

        if (!$profile) {
            throw new NotFoundHttpException('Магазин не найден.');
        }

        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = new ShopFeedbackEntity();
        $rating = $shopFeedback->getAverageShopRating(Yii::$app->request->queryParams['shop_id']);

        return [
            'shop_name' => $profile->name,
            'avatar' => $profile->image,
            'rating' => $rating
        ];
    }
}
