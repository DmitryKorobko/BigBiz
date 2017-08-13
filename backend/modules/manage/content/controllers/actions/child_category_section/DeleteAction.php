<?php
namespace backend\modules\manage\content\controllers\actions\child_category_section;

use common\models\child_category_section\ChildCategorySectionEntity;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteAction
 *
 * @package backend\modules\manage\content\controllers\actions\child_category_section
 */
class DeleteAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function run($id)
    {
       /** @var $category ChildCategorySectionEntity.php */
        $category = ChildCategorySectionEntity::findOne($id);
        try {
            $category->delete();
            Yii::$app->getSession()->setFlash('success', "Категория удалена успешно!");
            return $this->controller->redirect(['/manage/content/child-category-section']);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка при удалении категории. Попробуйте еще раз или обратитесь в поддержку!");
            return $this->controller->redirect(['/manage/content/child-category-section']);
        }
    }
}