<?php
namespace backend\modules\moderator\content\controllers\actions\child_category_section;

use common\models\child_category_section\ChildCategorySectionEntity;
use Yii;
use yii\{
    base\Action, base\Exception, web\ErrorHandler
};

/**
 * Class CreateAction
 *
 * @package backend\modules\moderator\content\controllers\actions\child_category_section
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/moderator/content/views/child_category_section/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /* @var  $category ChildCategorySectionEntity */
        $category = new ChildCategorySectionEntity();
        $category->scenario = ChildCategorySectionEntity::SCENARIO_CREATE;

        if ($category->load(Yii::$app->request->post()) && $category->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($category->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', "Категория добавлена успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::error(ErrorHandler::convertExceptionToString($e));
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка при добавлении категории!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [
            'category' => $category
        ]);
    }
}