<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use common\models\{
    comment\CommentEntity, theme\ThemeEntity
};
use Yii;
use yii\base\Action;
use backend\models\BackendUserEntity;

/**
 * Class ViewAction
 *
 * @package backend\modules\manage\administrator\controllers\actions\moderator
 */
class ViewAction extends Action
{
    public $view = '@backend/modules/manage/users/views/moderator/view';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'view';
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function run($id)
    {
        /** @var  $modelTheme ThemeEntity.php */
        $modelTheme = new ThemeEntity();
        $themeParams = ['shop_id' => $id, 'limit' => 10];
        if (isset(Yii::$app->request->queryParams['ThemeEntity'])) {
            $themeParams = array_merge($themeParams, Yii::$app->request->queryParams);
        }
        $dataProviderTheme = $modelTheme->search($themeParams);

        /** @var  $comment CommentEntity.php */
        $comment = new CommentEntity();
        $commentParams = ['shop_id' => $id, 'limit' => 10];
        if (isset(Yii::$app->request->queryParams['CommentEntity'])) {
            $commentParams = array_merge($commentParams, Yii::$app->request->queryParams);
        }

        $dataProviderComments = $comment->search($commentParams);

        /* @var $modelUser BackendUserEntity */
        $modelUser = BackendUserEntity::find()->where(['id' => $id])->one();

        if (!$modelUser){
            $modelUser = new BackendUserEntity();
        }

        return $this->controller->render($this->view, [
            'comment'                   => $comment,
            'modelTheme'                => $modelTheme,
            'dataProviderTheme'         => $dataProviderTheme,
            'dataProviderComments'      => $dataProviderComments,
            'modelUser'                 => $modelUser
        ]);
    }
}