<?php

namespace rest\modules\api\v1\message\controllers;

use common\models\{
    message\MessageEntity, user_confidentiality\UserConfidentialityEntity
};
use rest\modules\api\v1\message\controllers\actions\{
    ChatHistoryAction, CountNewAction, CreateAction, DeleteChatAction, ListChatsAction, UpdateAction, DeleteAction,
    CleaningChatAction
};
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Class MessageController
 *
 * @package rest\modules\api\v1\message\controllers
 */
class MessageController extends ActiveController
{
    /** @var  $modelClass MessageEntity.php */
    public $modelClass = MessageEntity::class;

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

        $actions['list-chats'] = [
            'class'      => ListChatsAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete-chat'] = [
            'class'      => DeleteChatAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['create'] = [
            'class'      => CreateAction::class,
            'modelClass' => $this->modelClass,
        ];

        $actions['update'] = [
            'class'      => UpdateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['history-chat'] = [
            'class'      => ChatHistoryAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['count-new'] = [
            'class'      => CountNewAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete'] = [
            'class'      => DeleteAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['cleaning-chat'] = [
            'class'      => CleaningChatAction::class,
            'modelClass' => UserConfidentialityEntity::class
        ];

        return $actions;
    }
}
