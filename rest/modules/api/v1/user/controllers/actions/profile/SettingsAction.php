<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    models\user_profile\UserProfileEntity, behaviors\AccessUserBehavior
};
use Yii;
use yii\rest\Action;

/**
 * Class SettingsAction Action
 *
 * @mixin AccessUserBehavior
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class SettingsAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of getting user profile settings
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function run()
    {
        /** @var  $userProfile UserProfileEntity.php */
        $userProfile = new $this->modelClass();

        return $userProfile->getUserProfileSettings(Yii::$app->user->identity->getId());
    }
}
