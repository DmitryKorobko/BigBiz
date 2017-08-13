<?php

namespace rest\modules\api\v1\user\controllers\actions\user;

use rest\models\RestUser;
use yii\rest\Action;
use common\behaviors\AccessUserBehavior;

/**
 * Class CommonListFavoritesAction Action
 *
 * @mixin AccessUserBehavior
 * @package rest\modules\api\v1\user\controllers\actions\user
 */
class CommonListFavoritesAction extends Action
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
     * @return string
     */
    public static function getActionName()
    {
        return 'common-list-favorites';
    }

    /**
     * Action of getting common favorites list (products, themes)
     * @return array
     */
    public function run()
    {
        /** @var  $user RestUser.php */
        $user = new $this->modelClass();

        return $user->getCommonUserListFavorites();
    }
}
