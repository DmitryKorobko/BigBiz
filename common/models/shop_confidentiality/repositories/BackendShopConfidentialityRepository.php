<?php
namespace common\models\shop_confidentiality\repositories;

use common\models\shop_confidentiality\ShopConfidentialityEntity;
use Yii;
use yii\{
    web\HttpException, web\ServerErrorHttpException
};

/**
 * Class BackendShopConfidentialityRepository
 *
 * @package common\models\shop_confidentiality\repositories
 */
trait BackendShopConfidentialityRepository
{
    /**
     * Method of get shop confidentiality. Using REST API
     *
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function getShopConfidentiality(): array
    {
        $userConfidentiality = ShopConfidentialityEntity::find()
            ->select([
                'show_status_online',
                'view_page_access',
                'send_messages_access',
                'frequency_history_cleaning'
            ])
            ->where(['user_id' => Yii::$app->user->identity->getId()])
            ->asArray()
            ->one();

        return $userConfidentiality;

    }

    /**
     * Method for create shop profile confidentiality after registration
     *
     * @param $userId
     * @return bool
     */
    public function createShopProfileConfidentiality($userId): bool
    {
        $userConfidentiality = $this;
        $userConfidentiality->setScenario(ShopConfidentialityEntity::SCENARIO_CREATE);
        $userConfidentiality->setAttribute('user_id', $userId);

        return $userConfidentiality->save();
    }
}