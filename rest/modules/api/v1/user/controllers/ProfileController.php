<?php
namespace rest\modules\api\v1\user\controllers;

use rest\models\RestUser;
use yii\{
    filters\auth\HttpBearerAuth, rest\ActiveController
};
use common\models\{
    user_profile\UserProfileEntity, answer\AnswerEntity, user\UserEntity
};
use rest\modules\api\v1\user\controllers\actions\profile\{
    UpdatePasswordAction, GetProfileAction, UpdateProfileAction, SideMenuAction, AnswersAction, UserProfileAction,
    SettingsAction, DeleteProfileAction, UpdateAvatarAction
};

/**
 * Class ProfileController
 *
 * @package rest\modules\api\v1\user\controllers
 */
class ProfileController extends ActiveController
{
    /** @var  $modelClass UserProfileEntity.php */
    public $modelClass = UserProfileEntity::class;

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

        $actions['update-profile'] = [
            'class'      => UpdateProfileAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['update-password'] = [
            'class'      => UpdatePasswordAction::class,
            'modelClass' => RestUser::class
        ];

        $actions['get-profile'] = [
            'class'      => GetProfileAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['user-profile'] = [
            'class'      => UserProfileAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['side-menu'] = [
            'class'      => SideMenuAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['answers'] = [
            'class'      => AnswersAction::class,
            'modelClass' => AnswerEntity::class
        ];

        $actions['settings'] = [
            'class'      => SettingsAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['update-avatar'] = [
            'class'      => UpdateAvatarAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete-profile'] = [
            'class'      => DeleteProfileAction::class,
            'modelClass' => UserEntity::class
        ];

        return $actions;
    }
}
