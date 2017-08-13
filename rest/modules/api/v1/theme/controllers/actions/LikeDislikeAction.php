<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};
use common\{
    behaviors\AccessUserStatusBehavior, models\theme\ThemeEntity, models\theme_user_like_show\ThemeUserLikeShowEntity,
    behaviors\ValidatePostParameters
};

/**
 * Class LikeDislikeAction Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class LikeDislikeAction extends Action
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
                'inputParams' => ['theme_id', 'like']
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
    protected function beforeRun()
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of like or dislike theme
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $postData = Yii::$app->getRequest()->getBodyParams();

        /** @var  $themeModel ThemeEntity.php */
        $theme = ThemeEntity::findOne(['id' => $postData['theme_id']]);
        if (!$theme) {
            throw new NotFoundHttpException('Тема не найдена.');
        }

        /** @var  $themeUserLikeShowModel ThemeUserLikeShowEntity.php */
        $themeUserLikeShowModel = new ThemeUserLikeShowEntity();
        $themeUserLikeShowModel->scenario = ThemeUserLikeShowEntity::SCENARIO_CREATE;

        if (!$themeUserLikeShowModel->setLikeOrDislikeThemeByUser(
            $theme['id'], Yii::$app->user->identity->getId(), $postData['like']
        )) {
            throw new ServerErrorHttpException('Произошла ошибка.');
        }
        Yii::$app->getResponse()->setStatusCode(200, 'OK');
        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => 'Like упешно добавлен'
        ];
    }
}
