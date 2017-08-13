<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\device\DeviceEntity;
use rest\modules\api\v1\user\controllers\actions\device\CreateAction;
use yii\{
    filters\auth\HttpBearerAuth, rest\ActiveController
};

/**
 * Class DeviceController
 *
 * @package rest\modules\api\v1\user\controllers
 */
class DeviceController extends ActiveController
{
    /** @var  $modelClass DeviceEntity.php */
    public $modelClass = DeviceEntity::class;

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

        $actions['create'] = [
            'class'      => CreateAction::class,
            'modelClass' => DeviceEntity::class
        ];

        return $actions;
    }
}
