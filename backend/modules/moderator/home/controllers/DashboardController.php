<?php
namespace backend\modules\moderator\home\controllers;

use backend\modules\moderator\home\controllers\actions\{
    IndexAction, UpdateStatusOnlineAction
};
use yii\{
    filters\AccessControl, web\Controller
};
use common\models\answer\AnswerEntity;
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class DashboardController
 *
 * @package backend\modules\moderator\home\controllers
 */
class DashboardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update-status-online'],
                        'allow'   => true,
                        'roles'   => ['moder']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        /** @var  $user BackendUserEntity*/
        $user = BackendUserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);
        /** @var  $answer AnswerEntity*/
        $answer = new AnswerEntity();
        $this->view->params = [
            'user_created_at' => $user->created_at,
            'newAnswers'      => $answer->getCountNewCommentReplies(),
            'answers'         => $answer->getListAnswersByType(AnswerEntity::STATUS_UNREAD,
                true, AnswerEntity::TYPE_REPLY_COMMENT),
            'status'          => BackendUserEntity::findIdentity(Yii::$app->user->id)->status,
            'statusOnline'    => BackendUserEntity::findIdentity(Yii::$app->user->id)->status_online
        ];

        $actions = parent::actions();

        $actions['error'] = [
            'class' => 'yii\web\ErrorAction',
        ];
        $actions['index'] = [
            'class' => IndexAction::class,
        ];
        $actions['update-status-online'] = [
            'class' => UpdateStatusOnlineAction::class,
        ];

        return $actions;
    }
}
