<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    models\answer\AnswerEntity, behaviors\AccessUserBehavior, behaviors\ValidateGetParameters
};
use Yii;
use yii\{
    rest\Action, data\ArrayDataProvider
};

/**
 * Class AnswersAction
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserBehavior
 *
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class AnswersAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'answers';
    }

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
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of getting answers for user.
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