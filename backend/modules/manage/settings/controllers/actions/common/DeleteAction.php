<?php
namespace backend\modules\manage\settings\controllers\actions\common;

use common\models\settings\SettingsEntity;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteAction
 *
 * @package backend\modules\manage\settings\controllers\actions\common
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
       /** @var $banner SettingsEntity.php */
        $settings = SettingsEntity::findOne($id);
        try {
            $settings->delete();
            Yii::$app->getSession()->setFlash('success', "Пункт общих настроек удален успешно!");
            return $this->controller->redirect(['/manage/settings/common/index']);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка. Попробуйте еще раз или обратитесь в поддержку!");
            return $this->controller->redirect(['/manage/settings/common/index']);
        }
    }
}