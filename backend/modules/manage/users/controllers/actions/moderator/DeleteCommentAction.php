<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use common\models\comment\CommentEntity;
use Yii;
use yii\base\{
    Action, ErrorHandler
};

/**
 * Class DeleteCommentAction
 *
 * @package backend\modules\manage\users\controllers\actions\moderator
 */
class DeleteCommentAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete-comment';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var $comment CommentEntity.php */
        $comment = CommentEntity::findOne(['id' => $id]);
        $userId = $comment->created_by;
        try {
            $comment->delete();
            Yii::$app->getSession()->setFlash('success', "Комментарий удален успешно!");
            return $this->controller->redirect('view?id=' . $userId);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при удалении комментария! 
            Смотреть логи!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}