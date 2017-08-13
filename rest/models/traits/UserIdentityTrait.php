<?php

namespace rest\models\traits;

/**
 * UserIdentity Trait
 *
 * @package rest\models\traits
 */
trait UserIdentityTrait
{
    /**
     * Returns id user
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Finds user by id
     *
     * @param int|string $id
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
}
