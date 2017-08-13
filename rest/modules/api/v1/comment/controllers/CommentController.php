<?php
namespace rest\modules\api\v1\comment\controllers;

use common\models\comment\CommentEntity;
use rest\modules\api\v1\comment\controllers\actions\{
    IndexAction, LikeAction, ListAction, CreateAction, DeleteAction, UpdateAction
};
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Class CommentController
 *
 * @package rest\modules\api\v1\comment\controllers
 */
class CommentController extends ActiveController
{
    /** @var  $modelClass CommentEntity.php */
    public $modelClass = CommentEntity::class;

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

        $actions['index'] = [
            'class'      => IndexAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['create'] = [
            'class'      => CreateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete'] = [
            'class'      => DeleteAction::class,
            'modelClass' => $this->modelClass,
        ];

        $actions['like'] = [
            'class'      => LikeAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['list'] = [
            'class'      => ListAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['update'] = [
            'class'      => UpdateAction::class,
            'modelClass' => $this->modelClass,
        ];

        return $actions;
    }
}
