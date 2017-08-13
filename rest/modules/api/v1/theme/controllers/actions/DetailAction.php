<?php

namespace rest\modules\api\v1\theme\controllers\actions;

use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException
};
use common\{
    models\theme\ThemeEntity, models\theme_user_like_show\ThemeUserLikeShowEntity, behaviors\ValidateGetParameters
};

/**
 * Class Detail Action
 *
 * @mixin ValidateGetParameters
 *
 * @package rest\modules\api\v1\theme\controllers\actions
 */
class DetailAction extends Action
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
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['id']
            ],
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting theme details
     *
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function run(): array
    {
        /** @var  $theme ThemeEntity.php */
        $theme = ThemeEntity::findOne(['id' => Yii::$app->request->queryParams['id']]);
        if (!$theme) {
            throw new NotFoundHttpException('Тема не найдена.');
        }

        /** @var  $themeModel ThemeEntity.php */
        $themeModel = new $this->modelClass;
        $theme = $themeModel->getThemeDetail(Yii::$app->request->queryParams);

        if ($theme) {
            /** @var  $themeUserLikeShowModel ThemeUserLikeShowEntity.php */
            $themeUserLikeShowModel = new ThemeUserLikeShowEntity();
            if (!$themeUserLikeShowModel->isShowByUser($theme['id'], Yii::$app->user->identity->getId())) {
                $themeUserLikeShowModel->setShowFlagByUser($theme['id'], Yii::$app->user->identity->getId());
                $themeModel->increaseViewCountTheme($theme['id']);
            }
            return $theme;
        }
        return [];
    }
}