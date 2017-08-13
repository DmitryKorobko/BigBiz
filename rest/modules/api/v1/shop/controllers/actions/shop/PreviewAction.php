<?php

namespace rest\modules\api\v1\shop\controllers\actions\shop;

use common\{
    models\shop_profile\ShopProfileEntity, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException
};

/**
 * Class PreviewAction
 *
 * @mixin ValidateGetParameters
 *
 * @package rest\modules\api\v1\shop\controllers\actions\shop
 */
class PreviewAction extends Action
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
     * Action of getting shop preview information with themes and products
     *
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function run()
    {
        /** @var  $profile ShopProfileEntity.php */
        $profile = ShopProfileEntity::findOne(['user_id' => Yii::$app->request->queryParams['shop_id']]);

        if (!$profile) {
            throw new NotFoundHttpException('Магазин не найден.');
        }

        /** @var  $model ShopProfileEntity.php */
        $model = new $this->modelClass;

        return $model->getPreviewInformation(Yii::$app->request->queryParams['shop_id']);
    }
}