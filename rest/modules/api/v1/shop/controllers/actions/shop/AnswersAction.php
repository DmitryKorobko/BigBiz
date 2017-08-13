<?php

namespace rest\modules\api\v1\shop\controllers\actions\shop;

use common\{
    models\answer\AnswerEntity, behaviors\AccessShopBehavior, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    rest\Action, data\ArrayDataProvider
};

/**
 * Class AnswersAction
 *
 * @mixin ValidateGetParameters
 * @mixin AccessShopBehavior
 *
 * @package rest\modules\api\v1\shop\controllers\actions\shop
 */
class AnswersAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['status']
            ],
            [
                'class'   => AccessShopBehavior::className(),
                'message' => 'Данная страница доступна только для магазинов.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of getting answers for shop.
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run(): ArrayDataProvider
    {
        /** @var  $review AnswerEntity.php */
        $review = new $this->modelClass;

        return $review->getListAnswers(Yii::$app->user->identity->getId(), Yii::$app->request->queryParams['status']);
    }
}