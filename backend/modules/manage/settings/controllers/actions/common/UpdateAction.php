<?php
namespace backend\modules\manage\settings\controllers\actions\common;

use common\models\settings\SettingsEntity;
use Yii;
use yii\base\Action;
use yii\base\Exception;

/**
 * Class UpdateAction
 *
 * @package backend\modules\manage\settings\controllers\actions\common
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/manage/settings/views/common/update';

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
        $settings = SettingsEntity::findOne($id);
        $settings->scenario = SettingsEntity::SCENARIO_UPDATE;

        $postData = Yii::$app->request->post();

        if ($settings->load($postData) && $settings->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($settings->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', "Пункт настроек обновлен успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка при изменении пункта настроек!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [ 'settings' => $settings ]);
    }
}