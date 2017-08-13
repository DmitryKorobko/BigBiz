<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    models\user_profile\UserProfileEntity, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException
};

/**
 * Class UserProfileAction Action
 *
 * @mixin ValidateGetParameters
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UserProfileAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['user_id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting anyone user profile information
     *
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function run()
    {
        /**
         * @var  $user UserProfileEntity.php
         */
        $user = UserProfileEntity::findOne(['user_id' => Yii::$app->request->queryParams['user_id']]);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        /** @var  $userProfile UserProfileEntity.php */
        $userProfile = new $this->modelClass();

        return $userProfile->getProfileInformation(Yii::$app->request->queryParams['user_id']);
    }
}
