<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    models\user_profile\UserProfileEntity, behaviors\AccessUserBehavior,
    models\user\UserEntity
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class DeleteProfile Action
 *
 * @mixin AccessUserBehavior
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class DeleteProfileAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessUserBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of updating user profile information and settings
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        /**
         * @var $userProfile UserProfileEntity.php
         * @var $user UserEntity.php
         */
        $userProfile = UserProfileEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);

        if (!$userProfile) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $user = UserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);
        $user->setScenario(UserEntity::SCENARIO_UPDATE);
        $user->setAttribute('is_deleted', 1);

        if ($user->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode('200', 'OK');
            return $response->content = [
                'status'  => $response->statusCode,
                'message' => 'Профиль успешно удалён',
                'data'    => ['user_id' => $user->id]
            ];
        }
        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
            ошибке администарации приложения.');
    }
}
