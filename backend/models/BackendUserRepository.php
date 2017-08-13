<?php
namespace backend\models;

use common\models\{
    user_profile\UserProfileEntity, theme\ThemeEntity, comment\CommentEntity, shop_feedback\ShopFeedbackEntity,
    product_feedback\ProductFeedbackEntity, message\MessageEntity, user\UserEntity
};

use yii\data\ArrayDataProvider;
use Yii;

/**
 * Class BackendUserRepository
 *
 * @package backend\models
 */
trait BackendUserRepository
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string $role
     *
     * @return ArrayDataProvider
     */
    public function search($params, $role)
    {
        $query = BackendUserEntity::find()
            ->select(['user_profile.*', 'user.*'])
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->leftJoin(UserProfileEntity::tableName(), 'user_profile.user_id = user.id')
            ->where(['auth_assignment.item_name' => $role, 'user.is_deleted' => 0]);

        if(!empty($params['id'])) {
            $query->andWhere(['user.id' => $params['id']]);
        }

        // add conditions that should always apply here
        $dataProvider = new ArrayDataProvider([
            'allModels'  => $query->asArray()->all(),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Method of creating folder of images for each new user
     */
    public function createFoldersForImages()
    {
        $pathSource = \Yii::getAlias("@webroot/images/uploads/user-{$this->id}/filemanager/source");
        if (!is_dir($pathSource)) {
            mkdir($pathSource, 0755, true);
        }

        $pathThumbs = \Yii::getAlias("@webroot/images/uploads/user-{$this->id}/filemanager/thumbs");
        if (!is_dir($pathThumbs)) {
            mkdir($pathThumbs, 0755, true);
        }
    }

    /**
     * Method of getting count of user created themes, comments, messages, reviews about shops and products.
     *
     * @param $id
     * @return array
     */
    public function getActivity($id)
    {
        $activity = [
            'countThemes'         => ThemeEntity::find()->where(['user_id' => $id])->count(),
            'countComments'       => CommentEntity::find()->where(['created_by' => $id])->count(),
            'countMessages'       => MessageEntity::find()->where(['created_by' => $id])->count(),
            'countShopReviews'    => ShopFeedbackEntity::find()->where(['created_by' => $id])->count(),
            'countProductReviews' => ProductFeedbackEntity::find()->where(['user_id' => $id])->count()
        ];

        return $activity;
    }

    /**
     * Method of getting last visit time of user.
     *
     * @param $id
     * @return int
     */
    public function getLastVisitTime($id): int
    {
        return $lastVisitTime = UserProfileEntity::find()->select('updated_at')->where(['user_id' => $id])
            ->asArray()->one()['updated_at'];
    }

    /**
     * Method of getting reputation of user.
     *
     * @param $id
     * @return int
     */
    public function getReputation($id): int
    {
        if (!empty($reputation = UserProfileEntity::find()->select('reputation')->where(['user_id' => $id])
            ->asArray()->one()['reputation'])) {
            return $reputation;
        }

        return 0;
    }

    /**
     * Method of getting count of new users.
     *
     * @return array
     */
    public function getCountNewUsers(): array
    {
        $count = [
            'shops'     => 0,
            'customers' => 0
        ];
        $users = UserEntity::find()
            ->select(['id', 'created_at'])
            ->asArray()
            ->all();

        foreach ($users as $user) {
            if ((time() - $user['created_at']) < 86400) {

                $userModel = UserEntity::findOne(['id' => $user['id']]);
                $assignment = Yii::$app->authManager->getAssignment('shop', $userModel->getId());

                if ($assignment && $assignment->roleName === 'shop') {
                    $count['shops']++;
                } else {
                    $assignment = Yii::$app->authManager->getAssignment('user', $userModel->getId());

                    if ($assignment && $assignment->roleName === 'user') {
                        $count['customers']++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Method of getting count of all users.
     *
     * @return array
     */
    public function getCountAllUsers(): array
    {
        $count = [
            'shops'     => 0,
            'customers' => 0
        ];
        $users = UserEntity::find()
            ->select(['id', 'created_at'])
            ->asArray()
            ->all();

        foreach ($users as $user) {
            $userModel = UserEntity::findOne(['id' => $user['id']]);
            $assignment = Yii::$app->authManager->getAssignment('shop', $userModel->getId());

            if ($assignment && $assignment->roleName === 'shop') {
                $count['shops']++;
            } else {
                $assignment = Yii::$app->authManager->getAssignment('user', $userModel->getId());

                if ($assignment && $assignment->roleName === 'user') {
                    $count['customers']++;
                }
            }
        }

        return $count;
    }

}