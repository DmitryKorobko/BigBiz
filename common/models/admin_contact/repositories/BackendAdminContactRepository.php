<?php
namespace common\models\admin_contact\repositories;

use common\models\admin_contact\AdminContactEntity;
use Yii;

/**
 * Class BackendAdminContactRepository
 *
 * @package common\models\admin_contact\repositories
 */
trait BackendAdminContactRepository
{
    /**
     * Searching admin profile by $userId
     *
     * @param integer $userId
     * @return AdminContactEntity model
     */
    protected function searchProfile($userId)
    {
        return self::findOne(['user_id' => $userId]);
    }

    /**
     * Method of getting shop profile information
     *
     * @return AdminContactEntity
     */
    public static function getProfile()
    {
        /** @var  $modelProfile AdminContactEntity.php */
        $modelProfile = new AdminContactEntity();
        $model = $modelProfile->searchProfile(@Yii::$app->user->id);

        if (isset($model->id)) {
            $modelProfile = $model;
            $modelProfile->scenario = AdminContactEntity::SCENARIO_UPDATE;
        } else {
            $modelProfile->user_id = @Yii::$app->user->id;
            $modelProfile->scenario = AdminContactEntity::SCENARIO_CREATE;
        }

        return $modelProfile;
    }

    /**
     * Method of getting current admin name
     *
     * @return mixed
     */
    public static function getCurrentName()
    {
        /** @var  $profile AdminContactEntity.php */
        $profile = AdminContactEntity::find()->where(['user_id' => @Yii::$app->user->identity->getId()])->one();
        return ($profile) ? $profile->nickname : 'Админ';
    }

    /**
     * Method of getting current admin image
     *
     * @param $id
     * @return string
     */
    public static function getCurrentImage($id)
    {
        /** @var  $profile AdminContactEntity.php */
        $profile = AdminContactEntity::find()->where(['user_id' => $id])->one();
        $profileImage = @Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte/img/avatar5.png';
        if ($profile && $profile->avatar) {
            $pathImage = Yii::getAlias('@webroot') . str_replace('/admin', '', $profile->avatar);
            if (file_exists($pathImage)) {
                $profileImage = $profile->avatar;
            }
        }

        return $profileImage;
    }

    /**
     * Method checking for opportunity creating admin boss
     *
     * @return string
     */
    public static function bossCreatingOpportunity()
    {
        /** @var  $profile AdminContactEntity.php */
        $profile = AdminContactEntity::find()->where(['is_boss' => 1])->one();
        if (!$profile) {
            return true;
        }

        return false;
    }
}