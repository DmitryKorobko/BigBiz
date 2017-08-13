<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\db\Exception as ExceptionDb;
use Yii;

/**
 * Class ValidationExceptionFirstMessage
 * @package common\behaviors
 */
class ValidationExceptionFirstMessage extends Behavior
{
    /**
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    public static function throwModelException($modelErrors)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
    }

}