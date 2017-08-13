<?php
namespace backend\modules\moderator\content\controllers\actions\main_category_section;

use Yii;
use yii\base\{
    Action, ErrorHandler
};
use common\models\main_category_section\MainCategorySectionEntity;

/**
 * Class DeleteAction
 *
 * @package backend\modules\moderator\content\controllers\actions\main_category_section
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
       /** @var $category MainCategorySectionEntity.php */
        $category = MainCategorySectionEntity::findOne($id);
        try {
            $category->delete();
            Yii::$app->getSession()->setFlash('success', "Категория удалена успешно!");
            return $this->controller->redirect(['/moderator/content/main-category-section']);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка при удалении категории. Возможно данная категория является родительской!");
            return $this->controller->redirect(['/moderator/content/main-category-section']);
        }
    }
}