<?php
namespace common\models\user_profile;

use common\models\{
    comment\CommentEntity, comment_like\CommentLikeEntity, product_feedback\ProductFeedbackEntity,
    shop_feedback\ShopFeedbackEntity, theme_user_like_show\ThemeUserLikeShowEntity, answer\AnswerEntity,
    message\MessageEntity, user_confidentiality\UserConfidentialityEntity,
    user_notifications_settings\UserNotificationsSettingsEntity, user_reputation\UserReputationEntity
};
use Yii;
use yii\{
    web\ForbiddenHttpException, web\NotFoundHttpException, web\UploadedFile, db\ActiveQuery
};

/**
 * Class UserProfileRepository
 * @package common\models\user_profile
 * @var $this \common\models\user_profile\UserProfileEntity;
 */
trait UserProfileRepository
{
    /**
     * Method of getting profile information by userId
     *
     * @param $userId
     * @return \yii\db\ActiveQuery
     */
    public function findProfile($userId): ActiveQuery
    {
        return UserProfileEntity::find()->where(['user_id' => $userId]);
    }

    /**
     * Upload profile image
     *
     * @param UploadedFile $image
     */
    public function uploadAvatar(UploadedFile $image)
    {
        $imageName = Yii::$app->security->generateRandomString() . '.' . $image->extension;

        $path = Yii::getAlias('@webroot/images/upload/profile/') . $imageName;
        $image->saveAs($path, false);
        $image->name = Yii::getAlias("@web/images/upload/profile/{$imageName}");
        $this->avatar = $image;
    }

    /**
     * Method for create empty user profile after registration
     *
     * @param $userId
     * @param $nickname
     * @param $termsConfirm
     * @return bool
     */
    public function createUserProfile($userId, $nickname, $termsConfirm): bool
    {
        $profile = new UserProfileEntity();
        $profile->setScenario(UserProfileEntity::SCENARIO_CREATE);
        $profile->setAttributes([
            'user_id'       => $userId,
            'nickname'      => $nickname,
            'terms_confirm' => $termsConfirm
        ]);

        return $profile->save(false);
    }

    /**
     * Method of getting current user avatar
     *
     * @param $id
     * @return string
     */
    public static function getCurrentImage($id): string
    {
        /** @var  $profile UserProfileEntity.php */
        $profile = UserProfileEntity::find()->where(['user_id' => $id])->one();
        $profileImage = @Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte/img/avatar5.png';
        if ($profile && isset($profile->avatar)) {
            $profileImage = $profile->avatar;
        }

        return $profileImage;
    }

    /**
     * Method of getting user profile information
     *
     * @param $userId
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function getProfileInformation($userId)
    {
        $reputation = new UserReputationEntity();
        $userProfile = UserProfileEntity::find()
            ->select([
                'user_profile.id', 'user_profile.user_id', 'user_profile.nickname', 'user_profile.avatar',
                'user_profile.gender', 'user_profile.created_at as date_of_registration', 'user_profile.dob_day',
                'user_profile.dob_month', 'user_profile.dob_year', 'user_profile.status_message'
            ])
            ->where(['user_id' => $userId])
            ->asArray()
            ->one();

        if (!$userProfile) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $userProfile = [
            'profile'    => [
                'nickname'             => $userProfile['nickname'],
                'avatar'               => $userProfile['avatar'],
                'date_of_registration' => (int) $userProfile['date_of_registration'],
                'gender'               => (
                    ($userProfile['gender'] === UserProfileEntity::GENDER_MALE)
                        ? 'Мужской' : 'Женский'
                ),
                'date_of_birth'        => ['day' => (int) $userProfile['dob_day'], 'month' => (int) $userProfile['dob_month'],
                    'year' => (int) $userProfile['dob_year']
                ],
                'reputation'           => $reputation->getUserReputation($userId),
                'status_message'       => $userProfile['status_message'],
                'communication_status' => 'Болтун'
            ],
            'statistics' => [
                'count_of_likes'       => (
                    ThemeUserLikeShowEntity::find()->where(['user_id' => $userId])->count()
                    + CommentLikeEntity::find()->where(['user_id' => $userId])->count()
                ),
                'count_of_comments'    => (int) CommentEntity::find()->where(['created_by' => $userId])->count(),
                'count_of_reviews'     => (
                    ProductFeedbackEntity::find()->where(['user_id' => $userId])->count()
                    + ShopFeedbackEntity::find()->where(['created_by' => $userId])->count()
                )
            ]
        ];

        return $userProfile;
    }

    /**
     * Method of getting side menu of user.
     *
     * @param $userId
     * @return array
     */
    public function getUserSideMenu($userId): array
    {
        $answer = new AnswerEntity();
        $message = new MessageEntity();
        $userProfile = UserProfileEntity::find()
            ->select(['user_profile.user_id', 'user_profile.nickname', 'user_profile.avatar'])
            ->where(['user_profile.user_id' => $userId])
            ->asArray()
            ->one();

        $userProfile = [
            'user_id'            => (int) $userProfile['user_id'],
            'nickname'           => $userProfile['nickname'],
            'avatar'             => $userProfile['avatar'],
            'count_new_messages' => $message->getCountNewMessagesByCurrentUser(),
            'count_new_answers'  => $answer->getCountNewAnswers()
        ];

        return $userProfile;
    }

    /**
     * Method of getting profile settings of user.
     *
     * @param $userId
     * @throws NotFoundHttpException
     * @return array
     */
    public function getUserProfileSettings($userId): array
    {
        $confidentiality = new UserConfidentialityEntity();
        $notificationsSettings = new UserNotificationsSettingsEntity();
        $userProfile = UserProfileEntity::find()
            ->select([
                'user_profile.nickname', 'user_profile.avatar', 'user_profile.status_message', 'user_profile.gender',
                'user_profile.dob_day', 'user_profile.dob_month', 'user_profile.dob_year', 'user.email as email',
                'user.is_deleted as is_deleted'
            ])
            ->leftJoin('user', 'user.id = user_profile.user_id')
            ->where(['user_profile.user_id' => $userId])
            ->asArray()
            ->one();

        if ($userProfile['is_deleted']) {
            throw new NotFoundHttpException('Профиль удалён. Вы можете его восстановить.');
        }

        $userProfileSettings = [
            'avatar'                 => $userProfile['avatar'],
            'status_message'         => $userProfile['status_message'],
            'personal_information'   => [
                'email'         => $userProfile['email'],
                'nickname'      => $userProfile['nickname'],
                'gender'        => $userProfile['gender'],
                'date_of_birth' => ['day' => (int) $userProfile['dob_day'], 'month' => (int) $userProfile['dob_month'],
                    'year' => (int) $userProfile['dob_year']
                ],
            ],
            'confidentiality'        => $confidentiality->getUserConfidentiality(),
            'notifications_settings' => $notificationsSettings->getUserNotificationsSettings()
        ];

        return $userProfileSettings;
    }

    /**
     * Method of getting count of all users.
     *
     * @return int
     */
    public function getCountAllUsers(): int
    {
        return UserProfileEntity::find()-> count();
    }

    /**
     * Method of getting list of all users.
     *
     * @return array | null
     */
    public function getListTopUsers()
    {
        return UserProfileEntity::find()
            ->select(['user_id', 'nickname', 'avatar', 'gender', 'status_message',
                'user.status_online as status_online'])
            ->leftJoin('user', 'user.id = user_profile.user_id')
            ->limit(10)
            ->asArray()
            ->all();
    }
}