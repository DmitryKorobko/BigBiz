<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    models\user_profile\UserProfileEntity, behaviors\AccessUserBehavior,
    models\user_confidentiality\UserConfidentialityEntity,
    models\user_notifications_settings\UserNotificationsSettingsEntity,
    behaviors\ValidationExceptionFirstMessage
};
use Yii;
use yii\{
    rest\Action, web\HttpException, web\NotFoundHttpException, web\ServerErrorHttpException
};
use yii\base\{
    ErrorHandler, Exception
};
use yii\db\Exception as ExceptionDb;

/**
 * Class UpdateProfile Action
 *
 * @mixin AccessUserBehavior
 * @mixin ValidationExceptionFirstMessage
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdateProfileAction extends Action
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
     * @throws HttpException
     */
    public function run(): array
    {
        /**
         * @var $userProfile UserProfileEntity.php
         * @var $userNotificationsSettings UserNotificationsSettingsEntity.php
         * @var $userConfidentiality UserConfidentialityEntity.php
         */
        $userProfile = UserProfileEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);

        if (!$userProfile) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userConfidentiality = UserConfidentialityEntity::findOne([
                'user_id' => Yii::$app->user->identity->getId()
            ]);
            $userNotificationsSettings = UserNotificationsSettingsEntity::findOne([
                'user_id' => Yii::$app->user->identity->getId()
            ]);

            $userProfile->setScenario(UserProfileEntity::SCENARIO_UPDATE);
            $userProfile->load(Yii::$app->getRequest()->getBodyParams(), '');
            $userConfidentiality->setScenario(UserConfidentialityEntity::SCENARIO_UPDATE);
            $userConfidentiality->load(Yii::$app->getRequest()->getBodyParams(), '');
            $userNotificationsSettings->setScenario(UserNotificationsSettingsEntity::SCENARIO_UPDATE);
            $userNotificationsSettings->load(Yii::$app->getRequest()->getBodyParams(), '');

            if ($userProfile->save() && $userConfidentiality->save() && $userNotificationsSettings->save()) {
                $transaction->commit();

                Yii::$app->response->setStatusCode(200, 'OK');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Профиль успешно изменён',
                    'data'    => $userProfile->getUserProfileSettings(Yii::$app->user->identity->getId())
                ];
            } elseif ($userProfile->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($userProfile->errors);
            } elseif ($userConfidentiality->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($userConfidentiality->errors);
            } elseif ($userNotificationsSettings->hasErrors()) {
                ValidationExceptionFirstMessage::throwModelException($userNotificationsSettings->errors);
            }
        }  catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
                ошибке администарации приложения.');
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
            ошибке администарации приложения.');
    }
}
