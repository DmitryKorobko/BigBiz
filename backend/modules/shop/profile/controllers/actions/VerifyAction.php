<?php
namespace backend\modules\shop\profile\controllers\actions;

use common\models\shop_profile\ShopProfileEntity;
use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;

/**
 * Class VerifyAction
 *
 * @package backend\modules\shop\profile\controllers\actions
 */
class VerifyAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'verify';
    }

    /**
     * Getting data from ShopProfileEntity model. Also implements ajax for
     * no-refresh validation and create/update functions
     *
     * @return string
     */
    public function run()
    {
        /* @var $user BackendUserEntity */
        $user = BackendUserEntity::findIdentity(@Yii::$app->user->id);
        $user->scenario = BackendUserEntity::SCENARIO_VERIFY_PROFILE;

        if ($user->status === BackendUserEntity::STATUS_UNVERIFIED) {
            $verificationCode = Yii::$app->request->post('BackendUserEntity')['verification_code'];

            if (strval($user->verification_code) == $verificationCode &&
                $user->setStatus(BackendUserEntity::STATUS_VERIFIED)) {
                Yii::$app->getSession()->setFlash('success', 'Код верификации подтвержден!');
            } else {
                Yii::$app->getSession()->setFlash('error', 'Код верификации неверен!');
            }
        }

        return $this->controller->redirect('index');
    }
}