<?php
namespace common\models\shop_profile\repositories;

use common\models\shop_profile\ShopProfileEntity;
use Yii;

/**
 * Class BackendShopProfileRepository
 *
 * @package common\models\shop_profile\repositories
 */
trait BackendShopProfileRepository
{
    /**
     * Find end date shop category
     *
     * @param $userId
     * @return int
     */
    public static function findCategoryEnd($userId)
    {
        $model = self::findOne(['user_id' => $userId]);
        return (isset($model->category_end)) ? $model->category_end : null;
    }

    /**
     * Searching shop profile by $userId
     *
     * @param integer $userId
     * @return ShopProfileEntity model
     */
    protected function searchProfile($userId)
    {
        return self::findOne(['user_id' => $userId]);
    }

    /**
     * Method of getting shop profile information
     *
     * @return ShopProfileEntity
     */
    public static function getProfile()
    {
        /** @var  $modelProfile ShopProfileEntity.php */
        $modelProfile = new ShopProfileEntity();
        $model = $modelProfile->searchProfile(@Yii::$app->user->id);

        if (isset($model->id)) {
            $modelProfile = $model;
            $modelProfile->towns = $modelProfile->cities;
            $modelProfile->scenario = ShopProfileEntity::SCENARIO_UPDATE;
        } else {
            $modelProfile->user_id = @Yii::$app->user->id;
            $modelProfile->scenario = ShopProfileEntity::SCENARIO_CREATE;
            $modelProfile->work_start_time = '09:00';
            $modelProfile->work_end_time = '18:00';
        }

        return $modelProfile;
    }

    /**
     * Method of getting current shop name
     *
     * @return mixed
     */
    public static function getCurrentName()
    {
        /** @var  $profile ShopProfileEntity.php */
        $profile = ShopProfileEntity::find()->where(['user_id' => @Yii::$app->user->identity->getId()])->one();
        return ($profile) ? $profile->name : @Yii::$app->user->identity->username;
    }

    /**
     * Method of getting current shop image
     *
     * @param $id
     * @return string
     */
    public static function getCurrentImage($id)
    {
        /** @var  $profile ShopProfileEntity.php */
        $profile = ShopProfileEntity::find()->where(['user_id' => $id])->one();
        $profileImage = @Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte/img/avatar5.png';
        if ($profile && $profile->image) {
            $pathImage = Yii::getAlias('@webroot') . str_replace('/admin', '', $profile->image);
            if (file_exists($pathImage)) {
                $profileImage = $profile->image;
            }
        }

        return $profileImage;
    }

    /**
     * Method of getting count of new shops.
     *
     * @return int
     */
    public function getCountNewShops(): int
    {
        $count = 0;
        $users = UserEntity::find()
            ->select(['id', 'created_at'])
            ->asArray()
            ->all();

        foreach ($users as $user) {
            /** @var  $shop ShopProfileEntity.php */
            $shop = ShopProfileEntity::findOne(['user_id' => $user['id']]);
            if (((time() - $user['created_at']) < 86400) && ($shop)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Method of getting user email
     *
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->user->email;
    }
}