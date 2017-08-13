<?php

namespace common\behaviors;

use Yii;
use yii\{
    behaviors\AttributeBehavior, web\BadRequestHttpException
};

/**
 * Class ValidateReportParameters
 *
 * @property array $params
 * @package common\behaviors
 */
class ValidateGetParameters extends AttributeBehavior
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
            $param = Yii::$app->request->queryParams;
            if (!isset(Yii::$app->request->queryParams[$nameParam])) {
                throw new BadRequestHttpException("Параметр {$nameParam} является обязательным.");
            }
            $this->owner->params[$nameParam] = (int)$param;
        }
    }
}
