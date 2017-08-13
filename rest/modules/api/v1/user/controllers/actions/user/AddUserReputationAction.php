<?php

namespace rest\modules\api\v1\user\controllers\actions\user;

use Yii;
use yii\{
    rest\Action, web\HttpException
};
use common\{
    behaviors\AccessUserStatusBehavior, behaviors\ValidatePostParameters, models\user_reputation\UserReputationEntity
};

/**
 * Class AddUserReputationAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\user\controllers\actions\user
 */
class AddUserReputationAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'add-user-reputation';
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
                'class'       => ValidatePostParameters::className(),
                'inputParams' => [
                    'recipient_id', 'action', 'text'
                ]
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
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
     * Action of add user reputation by userID
     * @return array
     * @throws HttpException
     */
    public function run(): array
    {
       /** @var  $userReputation UserReputationEntity.php */
        $userReputation = new UserReputationEntity();

        return $userReputation->addUserReputation(Yii::$app->request->post());
    }
}