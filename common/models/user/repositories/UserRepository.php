<?php
namespace common\models\user\repositories;

use common\models\user\UserEntity;
use common\models\user_profile\UserProfileEntity;
use Yii;

/**
 * Trait UserRepository
 *
 * @package common\models\user\repositories
 */
trait UserRepository
{
    /**
     * Setting user status to $status
     *
     * @param $status
     * @return bool
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->verification_code = null;
        if ($this->save(false)) {
            return true;
        }

        return false;
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param $email
     * @return int
     */
    public static function findStatusByEmail($email)
    {
        return static::findOne(['email' => $email])->status;
    }

    /**
     * @param $id
     * @return int
     */
    public static function findStatusById($id)
    {
        return static::findOne(['id' => $id])->status;
    }

    /**
     * Find user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_VERIFIED,
        ]);
    }

    /**
     * Find one user by $authKey
     *
     * @param $authKey
     * @return static
     */
    public static function findByAuthKey($authKey)
    {
        return static::findOne(['auth_key' => $authKey]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates verification code for shop registration confirm
     */
    public function generateVerificationCode()
    {
        $this->verification_code = rand(1000, 9999);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Verify user profile
     *
     * @param UserProfileEntity $model
     * @param $code
     * @return bool
     */
    public function verifyUserProfile(UserProfileEntity $model, $code = null)
    {
        $isVerify = true;

        if ($this->verification_code == $code) {
            $this->verification_code = null;
        }

        if ($this->verification_code !== $code || !$model->avatar || !$model->nickname) {
            $isVerify = false;
        }

        $this->status = ($isVerify) ? self::STATUS_VERIFIED : self::STATUS_UNVERIFIED;

        return $this->save(false);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusName($status)
    {
        switch ($status) {
            case UserEntity::STATUS_VERIFIED:
                return 'Верифицирован';
            case UserEntity::STATUS_UNVERIFIED:
                return 'Неверифицированый';
            case UserEntity::STATUS_GUEST:
                return 'Гость';
            case UserEntity::STATUS_BANNED:
                return 'Забанен';
            default:
                return 'Неверифицированый';
        }
    }

    /**
     * show online or offline user
     *
     * @param $id 
     * @return bool
     */
    public static function isOnline($id): bool
    {
        if (UserEntity::findOne(['id' => $id])->status_online) {
            return true;
        }

        return false;
    }
}
