<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\behaviors\{
    AccessUserStatusBehavior, ValidatePostParameters
};
use rest\models\RestUser;
use Yii;
use yii\rest\Action;

/**
 * Class UpdatePasswordAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\user\controllers\actions\profile;
 */
class UpdatePasswordAction extends Action
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
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['current_password', 'confirm', 'password_hash']
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->checkUserRole();
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action for updating password in "settings" tab.
     * Validate both passwords and saving password (or not if validate failed)
     *
     * @return array
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var  $model RestUser.php */
        $model = RestUser::findIdentity(Yii::$app->user->identity->getId());
        $model->scenario = RestUser::SCENARIO_UPDATE_PASSWORD;

        return $model->updateCurrentPassword(Yii::$app->getRequest()->getBodyParams());
    }
}