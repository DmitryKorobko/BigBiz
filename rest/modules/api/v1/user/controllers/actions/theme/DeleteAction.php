<?php

namespace rest\modules\api\v1\user\controllers\actions\theme;

use common\{
    models\theme\ThemeEntity, behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException, web\HttpException
};

/**
 * Class Delete Action
 *
 * @mixin AccessUserStatusBehavior
 * @package rest\modules\api\v1\user\controllers\actions\theme
 */
class DeleteAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
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
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of deleting user theme by ID
     *
     * @param $id
     * @return \yii\console\Response | \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException'
     * @throws HttpException
     */
    public function run($id)
    {
        /** @var  $theme ThemeEntity.php */
        $theme = ThemeEntity::findOne(['id' => $id, 'user_id' => Yii::$app->user->identity->getId()]);
        if (!$theme) {
            throw new NotFoundHttpException('Тема не найдена');
        }

        if ($theme && $theme->delete() === false) {
            throw new ServerErrorHttpException('Произошла ошибка. 
                Повторите попытку или сообщите об ошибке администарации приложения.');
        }

        Yii::$app->getResponse()->setStatusCode(200, 'OK');
        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => 'Тема успешно удалена',
            'data'    => ['theme_id' => (int) $id]
        ];
    }
}
