<?php
namespace rest\modules\api\v1\content\controllers;

use common\models\mobile_banner\MobileBannerEntity;
use rest\modules\api\v1\content\controllers\actions\banner\ListBannerAction;
use yii\{ filters\auth\HttpBearerAuth, rest\ActiveController };

/**
 * Class BannerController
 *
 * @package rest\modules\api\v1\content\controllers
 */
class BannerController extends ActiveController
{
    /** @var  $modelClass MobileBannerEntity.php */
    public $modelClass = MobileBannerEntity::class;

    /**
     * @var array
     */
    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['list'] = [
            'class'      => ListBannerAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
