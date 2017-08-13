<?php

namespace rest\modules\api\v1\support\controllers;

use common\models\feedback\Feedback;
use rest\modules\api\v1\support\controllers\actions\SendLetterAction;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class SupportController
 * @package rest\modules\api\v1\support\controllers
 */
class SupportController extends ActiveController
{
    /** @var $modelClass Feedback  */
    public $modelClass = Feedback::class;

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

        $actions['send-letter'] = [
            'class'      => SendLetterAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}