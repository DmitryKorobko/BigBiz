<?php
namespace backend\modules\moderator\content\controllers;

use yii\{
    filters\AccessControl, web\Controller, web\NotFoundHttpException
};
use backend\modules\moderator\content\controllers\actions\theme\{
    CreateAction, DeleteAction, IndexAction, UpdateAction, ChangeThemeStatusAction, ViewAction
};
use common\models\{
    answer\AnswerEntity, theme\ThemeEntity
};
use backend\models\BackendUserEntity;
use Yii;

/**
 * Class ThemeController
 *
 * @package backend\modules\moderator\content\controllers
 */
class ThemeController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'change-theme-status', 'view'],
                        'allow'   => true,
                        'roles'   => ['moder']
                    ]
                ],
                'denyCallback' => function () {
                    if (Yii::$app->user->getIsGuest()) {
                        $this->redirect(Yii::$app->request->baseUrl);
                    }
                }
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        if (!Yii::$app->user->getIsGuest()) {
            /** @var  $user BackendUserEntity */
            $user = BackendUserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);
            /** @var  $answer AnswerEntity */
            $answer = new AnswerEntity();
            $this->view->params = [
                'user_created_at' => $user->created_at,
                'newAnswers' => $answer->getCountNewCommentReplies(),
                'answers' => $answer->getListAnswersByType(AnswerEntity::STATUS_UNREAD,
                    true, AnswerEntity::TYPE_REPLY_COMMENT),
                'status' => BackendUserEntity::findIdentity(Yii::$app->user->id)->status,
                'statusOnline' => BackendUserEntity::findIdentity(Yii::$app->user->id)->status_online
            ];
        }

        $actions = parent::actions();

        $actions['error'] = [
            'class' => 'yii\web\ErrorAction',
        ];
        $actions['create'] = [
            'class' => CreateAction::class,
        ];
        $actions['delete'] = [
            'class' => DeleteAction::class,
        ];
        $actions['index'] = [
            'class' => IndexAction::class,
        ];
        $actions['update'] = [
            'class' => UpdateAction::class,
        ];
        $actions['change-theme-status'] = [
            'class' => ChangeThemeStatusAction::class
        ];
        $actions['view'] = [
            'class' => ViewAction::class
        ];

        return $actions;
    }

    /**
     * Find Theme model by ID.
     * @param integer $id
     * @return ThemeEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ThemeEntity::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Страница не найдена.');
    }
}
