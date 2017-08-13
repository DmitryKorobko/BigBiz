<?php

namespace rest\modules\api\v1\user\controllers\actions\user;

use common\behaviors\AccessUserStatusBehavior;
use rest\models\RestUser;
use yii\rest\Action;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class ChangeStatusOnlineAction
 *
 * @mixin AccessUserStatusBehavior
 * @package rest\modules\api\v1\authorization\controllers\actions
 */
class ChangeStatusOnlineAction extends Action
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
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /** Method of changing the online status of a user
     *
     * @throws ServerErrorHttpException
     * @return array
     */
    public function run(): array
    {
        /** @var  $user RestUser */
        $user = RestUser::findOne(Yii::$app->user->identity->getId());
        if ($user->status_online == false) {
            $user->status_online = 1;
        } else {
            $user->status_online = 0;
        }

        if ($user->save()) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->getResponse()->statusCode,
                'message' => 'Онлайн статус пользователя успешно изменён',
                'data'    => [
                    'id' => $user->id,
                    'is_online' => RestUser::isOnline($user->id)
                ]
            ];
        }

        throw new ServerErrorHttpException('Произошла ошибка при изменении онлайн статуса пользователя.');
    }
}