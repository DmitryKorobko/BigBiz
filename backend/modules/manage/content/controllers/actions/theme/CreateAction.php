<?php
namespace backend\modules\manage\content\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\Action;
use yii\base\Exception;

/**
 * Class CreateAction
 *
 * @package backend\modules\manage\content\controllers\actions\theme;
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/manage/content/views/theme/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * Creates theme.
     * If create is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function run()
    {
        /** @var  $themeModel ThemeEntity.php */
        $themeModel = new ThemeEntity();
        $themeModel->scenario = ThemeEntity::SCENARIO_CREATE;
        $postData = Yii::$app->request->post();

        /** @var  $lastTheme ThemeEntity.php */
        $lastTheme = ThemeEntity::find()->where(['user_id' => Yii::$app->user->identity->getId()])
            ->orderBy('sort desc')->limit(1)->one();
        /** Set sort column if shop does not set */
        if ($postData && empty($postData['ThemeEntity']['sort']) && $lastTheme) {
            $postData['ThemeEntity']['sort'] = ++$lastTheme->sort;
        }

        if ($themeModel->load($postData) && $themeModel->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($themeModel->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', "Тема {$themeModel->name} добавлена успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();

                Yii::$app->getSession()->setFlash('error',
                    "Произошла ошибка при добавлении темы - {$themeModel->name}!");
                return $this->controller->redirect('index');
            }
        }
        return $this->controller->render($this->view, ['theme' => $themeModel]);
    }
}