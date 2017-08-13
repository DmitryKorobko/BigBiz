<?php
namespace backend\modules\moderator\home\controllers\actions;

use common\models\{
    comment\CommentEntity, theme\ThemeEntity
};
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\moderator\home\controllers\actions
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/moderator/home/views/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function run()
    {
        /** @var  $theme ThemeEntity.php */
        $theme = new ThemeEntity();
        /** @var  $comment CommentEntity.php */
        $comment = new CommentEntity();

        return $this->controller->render($this->view, [
            'count_of_own_themes'          => $theme->getCountOwnThemes(),
            'count_of_own_comments'        => $comment->getCountComments(),
            'count_of_comments_for_themes' => $comment->getCountComments(true)
        ]);
    }
}