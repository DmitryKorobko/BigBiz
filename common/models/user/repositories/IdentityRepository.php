<?php
namespace common\models\user\repositories;

use yii\base\NotSupportedException;

/**
 * Class IdentityRepository
 *
 * @package common\models\user\repositories
 */
trait IdentityRepository
{
    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $result = static::findOne(['id' => $id, 'status' => self::STATUS_VERIFIED]);
        if (!$result) {
            $result = static::findOne(['id' => $id, 'status' => self::STATUS_UNVERIFIED]);
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @param $token
     * @param null $type
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
}