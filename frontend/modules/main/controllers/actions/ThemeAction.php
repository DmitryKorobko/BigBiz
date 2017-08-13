<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;
use common\models\{
    comment\CommentEntity, theme\ThemeEntity, theme_user_like_show\ThemeUserLikeShowEntity
};
use Yii;

/**
 * Class ThemeAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class ThemeAction extends Action
{
    public $view = '@frontend/modules/main/views/theme';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'theme';
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function run($id):string
    {
        /** @var  $theme ThemeEntity*/
        $theme = ThemeEntity::findOne(['id' => $id]);
        /** @var  $comment CommentEntity*/
        $comment = new CommentEntity();
        $commentsDataProvider = $comment->getListComments($theme->id);
        $comments = $commentsDataProvider->allModels;
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        $themeLikeDislike = ThemeUserLikeShowEntity::findOne(['theme_id' => $theme->id, 'user_id' => $userId]);
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');

        return $this->controller->render($this->view, [
            'theme'                => $theme,
            'themeCountLike'       => ThemeUserLikeShowEntity::find()->where(['theme_id' => $theme->id, 'like' => 1])
                ->count(),
            'themeCountDislike'    => ThemeUserLikeShowEntity::find()->where(['theme_id' => $theme->id, 'like' => 0])
                ->count(),
            'themeInFavorites'     => (($userId != 'quest') ? $theme->isFavoriteThemeByUser($theme->id, $userId)
                : false),
            'comments'             => $comments,
            'themeLikeDislike'     => (!empty($themeLikeDislike)) ? $themeLikeDislike['like'] : null,
            'dataCount'            => $commentsDataProvider->count,
            'allDataCount'         => $commentsDataProvider->totalCount,
            'userId'               => $userId
        ]);
    }
}