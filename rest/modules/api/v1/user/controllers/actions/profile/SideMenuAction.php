<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    models\user_profile\UserProfileEntity, behaviors\AccessUserBehavior
};
use Yii;
use yii\rest\Action;

/**
 * Class SideMenuAction
 *
 * @mixin AccessUserBehavior
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class SideMenuAction extends Action
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
    protected function beforeRun()
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'side-menu';
    }

    /**
     * Action of getting side menu of user.
     *
     * @return array
     */
    public function run(): array
    {
        /** @var  $answer UserProfileEntity.php */
        $answer = new $this->modelClass;

        return $answer->getUserSideMenu(Yii::$app->user->identity->getId());
    }
}