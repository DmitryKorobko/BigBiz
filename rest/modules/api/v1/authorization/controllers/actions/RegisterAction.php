<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use Yii;
use yii\{
    rest\Action, web\HttpException
};
use rest\models\RestUser;

/**
 * Class Register Action
 *
 * @package rest\modules\api\v1\authorization\controllers\actions\user
 */
class RegisterAction extends Action
{
    /**
     * Register user action
     * 
     * @return string
     */
    public static function getActionName ()
    {
        return 'register';
    }

    /**
     * Action register a new user
     *
     * @return array
     * @throws HttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run()
    {
        /** @var  $user RestUser.php */
        $user = new $this->modelClass;

        return $user->registerUser(Yii::$app->request->post());
    }
}
