<?php
namespace backend\modules\manage\home\controllers\actions;

use common\models\{
    comment\CommentEntity, product\ProductEntity, theme\ThemeEntity
};
use yii\base\Action;
use backend\models\BackendUserEntity;

/**
 * Class IndexAction
 *
 * @package backend\modules\manage\home\controllers\actions
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/manage/home/views/index';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'index';
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function run():string
    {
        /** @var  $user BackendUserEntity.php */
        $user = new BackendUserEntity();

        return $this->controller->render($this->view, [
            'count_of_all_products' => ProductEntity::find()->count(),
            'count_of_all_themes'   => ThemeEntity::find()->count(),
            'count_of_all_comments' => CommentEntity::find()->count(),
            'count_of_all_users'    => $user->getCountAllUsers()['customers'],
            'count_of_new_users'    => $user->getCountNewUsers()['customers'],
            'count_of_all_shops'    => $user->getCountAllUsers()['shops'],
            'count_of_new_shops'    => $user->getCountNewUsers()['shops']
        ]);
    }
}