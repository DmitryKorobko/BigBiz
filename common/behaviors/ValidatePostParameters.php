<?php

namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\web\BadRequestHttpException;

/**
 * Class ValidateReportParameters
 *
 * @property array $params
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\behaviors
 */
class ValidatePostParameters extends AttributeBehavior
{
    /**
     * @var array
     */
    public $inputParams = [];

    /**
     * @inheritdoc array
     */
    public function validationParams()
    {
        foreach ($this->inputParams as $nameParam) {
            $param = Yii::$app->request->getBodyParam($nameParam);
            if (!isset(Yii::$app->request->getBodyParams()[$nameParam])) {
                throw new BadRequestHttpException("Параметр {$nameParam} является обязательным.");
            }
            $this->owner->params[$nameParam] = (int)$param;
        }
    }
}
