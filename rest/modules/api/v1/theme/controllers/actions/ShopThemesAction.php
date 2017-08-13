<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use common\{
    models\shop_profile\ShopProfileEntity, models\theme\ThemeEntity, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    data\ArrayDataProvider, rest\Action, web\NotFoundHttpException
};

/**
 * Class Themes Action
 *
 * @mixin ValidateGetParameters
 *
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class ShopThemesAction extends Action
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
     * Action of getting list of shop themes
     *
     * @return \yii\data\ArrayDataProvider
     * @throws NotFoundHttpException
     */
    public function run(): ArrayDataProvider
    {
        /** @var $shop ShopProfileEntity.php */
        $shop = ShopProfileEntity::findOne(['user_id' => Yii::$app->request->queryParams['shop_id']]);
        if (!$shop) {
            throw new NotFoundHttpException('Магазин не найден.');
        }

        /** @var  $theme ThemeEntity.php */
        $theme = new $this->modelClass;
        return $theme->getListShopThemes(Yii::$app->request->queryParams);
    }
}