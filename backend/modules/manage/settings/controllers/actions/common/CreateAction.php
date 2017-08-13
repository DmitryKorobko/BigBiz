<?php
namespace backend\modules\manage\settings\controllers\actions\common;

use common\models\settings\SettingsEntity;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\ErrorHandler;

/**
 * Class CreateAction
 *
 * @package backend\modules\manage\settings\controllers\actions\common
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/manage/settings/views/common/create';

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
        /* @var  $settings SettingsEntity */
        $settings = new SettingsEntity();
        $settings->scenario = SettingsEntity::SCENARIO_CREATE;

        if ($settings->load(Yii::$app->request->post()) && $settings->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($settings->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success',
                        "Пункт общих настроек добавлен успешно! Свяжитесь с администрацией.");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::error(ErrorHandler::convertExceptionToString($e));
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка при добавлении пункта общих настроек!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [
            'settings' => $settings
        ]);
    }
}