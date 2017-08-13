<?php
namespace rest\modules\api\v1\content\controllers;

use common\models\comment\CommentEntity;
use rest\modules\api\v1\content\controllers\actions\image\SignS3ImagePolicyAction;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Class ImageController
 *
 * @package rest\modules\api\v1\content\controllers
 */
class ImageController extends ActiveController
{
    /** @var  $modelClass CommentEntity.php */
    public $modelClass = CommentEntity::class;

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

        $actions['sign-s3-image-policy'] = [
            'class'      => SignS3ImagePolicyAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
