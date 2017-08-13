<?php

namespace rest\modules\api\v1\user\controllers;

use rest\modules\api\v1\user\controllers\actions\user\{
    AddUserReputationAction, CommonListFavoritesAction, ChangeStatusOnlineAction
};
use yii\{
    rest\ActiveController, filters\auth\HttpBearerAuth
};
use rest\models\RestUser;

/**
 * Class UserController
 *
 * @package rest\modules\api\v1\user\controllers
 */
class UserController extends ActiveController
{
    /** @var  $modelClass RestUser.php */
    public $modelClass = RestUser::class;

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
    public function behaviors()
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
    public function actions()
    {
        $actions = parent::actions();

        $actions['common-list-favorites'] = [
            'class'      => CommonListFavoritesAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['add-user-reputation'] = [
            'class'      => AddUserReputationAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['change-status-online'] = [
            'class'      => ChangeStatusOnlineAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
