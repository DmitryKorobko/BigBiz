<?php
namespace backend\modules\moderator\content\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\{
    Action, Exception
};

/**
 * Class UpdateAction
 *
 * @package backend\modules\moderator\content\controllers\actions\theme
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/moderator/content/views/theme/update';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update';
    }

    /**
     * Updates theme.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function run($id)
    {
        /** @var  $themeModel ThemeEntity */
        $themeModel = $this->controller->findModel($id);
        $themeModel->scenario = ThemeEntity::SCENARIO_UPDATE;
        $postData = Yii::$app->request->post();

        /** Set sort column if shop does not set */
        if ($postData && empty($postData['ThemeEntity']['sort'])) {
            /** @var  $lastTheme ThemeEntity.php */
            $lastTheme = ThemeEntity::find()->where(['<>', 'id', $themeModel->id])
                ->andWhere(['user_id' => Yii::$app->user->identity->getId()])
                ->orderBy('sort desc')->limit(1)->one();
            $postData['ThemeEntity']['sort'] = ++$lastTheme->sort;
        }

        if ($themeModel->load($postData) && $themeModel->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($themeModel->save()) {

                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', "Тема обновлена успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error',
                    "Произошла ошибка при изменении темы - {$themeModel->name}!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, ['theme' => $themeModel]);
    }
}