<?php
namespace backend\modules\manage\content\controllers\actions\main_category_section;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use common\models\main_category_section\MainCategorySectionEntity;

/**
 * Class UpdateAction
 *
 * @package backend\modules\manage\content\controllers\actions\main_category_section
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/manage/content/views/main_category_section/update';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update';
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function run($id)
    {
        $category = MainCategorySectionEntity::findOne($id);
        if ($category->category_type != null) {
            Yii::$app->getSession()->setFlash('warning', "Данную категорию изменять нельзя!");
            return $this->controller->redirect('index');
        }
        $category->scenario = MainCategorySectionEntity::SCENARIO_UPDATE;

        $postData = Yii::$app->request->post();

        if ($category->load($postData) && $category->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($category->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', "Категория обновлена успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка при изменении категории!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [ 'category' => $category ]);
    }
}