<?php
namespace rest\models;

use common\models\{
    user_confidentiality\UserConfidentialityEntity, user_notifications_settings\UserNotificationsSettingsEntity,
    user_profile\UserProfileEntity, product\ProductEntity, theme\ThemeEntity,
    shop_notifications_settings\ShopNotificationsSettingsEntity, shop_confidentiality\ShopConfidentialityEntity,
    shop_profile\ShopProfileEntity
};
use Yii;
use rest\modules\api\v1\authorization\models\BlockToken;
use yii\base\Exception;
use yii\db\Exception as ExceptionDb;
use yii\web\{ErrorHandler, HttpException, ServerErrorHttpException};

/**
 * Class RestUserRepository
 *
 * @package rest\models
 */
trait RestUserRepository
{
    /**
     * Check the token for the block
     *
     * @param string $token
     * @return bool
     */
    public static function isBlocked($token)
    {
        if (BlockToken::find()->where(['token' => $token])->one()) {
            return true;
        }

        return false;
    }

    /**
     * Reset recovery code for user
     *
     * @return int
     * @throws ExceptionDb
     */
    private function resetRecoveryCode()
    {
        return Yii::$app->db->createCommand()
            ->update(self::tableName(), [
                'recovery_code'         => null,
                'created_recovery_code' => null
            ], ['id' => $this->getId()])->execute();
    }

    /**
     * Method of checking recovery code
     *
     * @param $recoveryCode
     * @param $createdRecoveryCode
     * @param $postData
     * @return bool
     */
    private function checkRecoveryCode($recoveryCode, $createdRecoveryCode, $postData)
    {
        if ($recoveryCode !== $postData['recovery_code']) {
            $this->addError('recovery_code', 'Код восстановления неверен!');
            return false;
        }
        if (!$createdRecoveryCode || $createdRecoveryCode + 3600 < time()) {
            $this->addError('created_recovery_code', 'Время кода восстановления истекло. Сгенерите новый!');
            return false;
        }
        return true;
    }

    /**
     * Adds token in the black list
     *
     * @param string $token
     * @return bool
     */
    public static function addBlackListToken($token)
    {
        if ($token) {
            if (self::isBlocked($token)) {
                return true;
            }
            /** @var $model BlockToken */
            $model = new BlockToken();
            $model->setScenario(BlockToken::SCENARIO_CREATE_BLOCK);

            $values = [
                'user_id'    => RestUser::getPayload($token, 'jti'),
                'expired_at' => RestUser::getPayload($token, 'exp'),
                'token'      => $token
            ];
            $model->attributes = $values;

            return $model->save();
        }

        return false;
    }

    /**
     * Register user
     *
     * @param $data
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function registerUser($data)
    {
        $this->setScenario(RestUser::SCENARIO_REGISTER);

        $verificationCode = rand(1000, 9999);
        $refreshToken = base64_encode(md5(time()) . md5(rand(1000, 9999)));
        $data = array_merge($data, [
            'verification_code' => $verificationCode,
            'status'            => RestUser::STATUS_UNVERIFIED,
            'refresh_token'     => $refreshToken
        ]);
        $this->setAttributes($data);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $transaction->commit();
            if ($this->save()) {
                if ($this->role === self::ROLE_USER) {
                    /**
                     * Creating user profile confidentiality and user profile notifications settings
                     * @var  $userNotificationsSettings UserNotificationsSettingsEntity.php
                     * @var  $userConfidentiality UserConfidentialityEntity.php
                     */
                    $userConfidentiality = new UserConfidentialityEntity();
                    $userConfidentiality->createUserProfileConfidentiality($this->id);
                    $userNotificationsSettings = new UserNotificationsSettingsEntity();
                    $userNotificationsSettings->createUserProfileNotificationsSettings($this->id);
                    if (!$userConfidentiality->save() || !$userNotificationsSettings->save()) {
                        $transaction->rollBack();
                        throw new HttpException(424, 'Произошла ошибка при регистрации.');
                    }

                    /** Checking user profile profile creation, if true - return data, false - rollback */
                    $userProfile = new UserProfileEntity();
                    if ($userProfile->createUserProfile($this->getId(), $this->name, $this->terms_confirm)) {
                        Yii::$app->response->setStatusCode(201, 'Created');
                        return [
                            'status'  => Yii::$app->response->statusCode,
                            'message' => 'Регистрация пользователя прошла успешно',
                            'data'    => [
                                'token'         => $this->getJWT(),
                                'refresh_token' => $refreshToken,
                                'user'  => [
                                    'id'         => $this->getId(),
                                    'email'      => $this->email,
                                    'name'       => $this->name,
                                    'status'     => $this->getCurrentStatus($this->status),
                                    'role'       => $this->role,
                                    'created_at' => $this->created_at,
                                    'updated_at' => $this->updated_at,
                                ]
                            ]
                        ];
                    } else {
                        $transaction->rollBack();
                        throw new HttpException(424, 'Произошла ошибка при регистрации.');
                    }
                } elseif ($this->role === self::ROLE_SHOP) {
                    /**
                     * Creating shop profile confidentiality and shop profile notifications settings
                     * @var  $shopNotificationsSettings ShopNotificationsSettingsEntity.php
                     * @var  $shopConfidentiality ShopConfidentialityEntity.php
                     */
                    $shopConfidentiality = new ShopConfidentialityEntity();
                    $shopConfidentiality->createShopProfileConfidentiality($this->id);
                    $shopNotificationsSettings = new ShopNotificationsSettingsEntity();
                    $shopNotificationsSettings->createShopProfileNotificationsSettings($this->id);
                    if (!$shopConfidentiality->save() || !$shopNotificationsSettings->save()) {
                        $transaction->rollBack();
                        throw new HttpException(424, 'Произошла ошибка при регистрации.');
                    }

                    /** Checking shop profile profile creation, if true - return data, false - rollback */
                    $shopProfile = new ShopProfileEntity();
                    if ($shopProfile->createShopProfile($this->getId(), $this->name)) {
                        Yii::$app->response->setStatusCode(201, 'Created');
                        return [
                            'status'  => Yii::$app->response->statusCode,
                            'message' => 'Регистрация магазина прошла успешно',
                            'data'    => [
                                'token'         => $this->getJWT(),
                                'refresh_token' => $refreshToken,
                                'user'  => [
                                    'id'         => $this->getId(),
                                    'email'      => $this->email,
                                    'status'     => $this->getCurrentStatus($this->status),
                                    'role'       => $this->role,
                                    'name'       => $this->name,
                                    'created_at' => $this->created_at,
                                    'updated_at' => $this->updated_at,
                                ]
                            ]
                        ];
                    } else {
                        $transaction->rollBack();
                        throw new HttpException(424, 'Произошла ошибка при регистрации.');
                    }
                }

                /** Отправка письма с кодом верификация аккаунта на email пользователя */
                Yii::$app->mailer->compose('@common/views/mail/sendVerificationCode-html.php',
                    ['email' => $this->email, 'verificationCode' => $verificationCode])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($this->email)
                    ->setSubject('Верификация аккаунта')
                    ->send();
            }
            $this->validationExceptionFirstMessage($this->errors);
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }

        throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
    }

    /**
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    private function validationExceptionFirstMessage($modelErrors)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
    }

    /**
     * Returns password hash
     *
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Method of recovering password
     *
     * @param $postData
     * @return bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function recoveryCode($postData)
    {
        $recoveryCode = $this->recovery_code;
        $createdRecoveryCode = $this->created_recovery_code;
        try {
            $this->setAttributes($postData);
            if ($this->validate() && $this->checkRecoveryCode($recoveryCode, $createdRecoveryCode, $postData)) {
                return $this->save();
            }
            $this->validationExceptionFirstMessage($this->errors);
        } catch (ExceptionDb $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при восстановлении пароля.');
        }
        throw new ServerErrorHttpException('Произошла ошибка при восстановлении пароля.');
    }

    /**
     * Method of getting current user status
     *
     * @param $status
     * @return string
     */
    public function getCurrentStatus($status)
    {
        switch ($status) {
            case RestUser::STATUS_UNVERIFIED:
                return 'UNVERIFIED';
            case RestUser::STATUS_VERIFIED:
                return 'VERIFIED';
            case RestUser::STATUS_GUEST:
                return 'GUEST';
            default:
                return 'UNVERIFIED';
        }
    }

    /**
     * Method of updating user password
     *
     * @param $postData
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function updateCurrentPassword($postData)
    {
        try {
            if (!Yii::$app->security->validatePassword($postData['current_password'], $this->password_hash)) {
                throw new ExceptionDb('Неверно введен старый пароль!');
            }
            $this->setAttributes($postData);
            if ($this->save()) {
                Yii::$app->getResponse()->setStatusCode(200, 'OK');
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Пароль успешно изменен'
                ];
            }
            $this->validationExceptionFirstMessage($this->errors);
        } catch (Exception $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при изменении пароля.');
        }
    }

    /**
     * Method of getting common list favorites (products, themes)
     * @return array
     */
    public function getCommonUserListFavorites()
    {
        /** @var  $themeModel ThemeEntity.php */
        $themeModel = new ThemeEntity();
        /** @var  $productModel ProductEntity.php */
        $productModel = new ProductEntity();

        return [
            'themes' => $themeModel->getFavoritesThemesByUser(5),
            'products' => $productModel->getFavoritesProductsByUser(5)
        ];
    }
}