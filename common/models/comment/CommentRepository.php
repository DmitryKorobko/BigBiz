<?php
namespace common\models\comment;

use common\models\theme\ThemeEntity;
use common\models\shop_profile\ShopProfileEntity;
use common\models\user\UserEntity;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class CommentRepository
 *
 * @package common\models\comment
 */
trait CommentRepository
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $shopId = isset($params['shop_id']) ? $params['shop_id'] : Yii::$app->user->identity->getId();
        $query = CommentEntity::find()->where(['created_by' => $shopId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['limit']) ? $params['limit'] : 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($params['CommentEntity'])) {
            $query->andFilterWhere(['=', 'theme_id', $params['CommentEntity']['theme_id']]);
        }

        if (!empty($this->created_date_range) && strpos($this->created_date_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->created_date_range);
            $query->andFilterWhere(['between', 'created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }

    /**
     * Search commentaries for current viewing theme
     * and return them as ActiveDataProvider
     *
     * @param $themeId
     * @return ActiveDataProvider
     */
    public function searchComments($themeId)
    {
        $query = self::find()->where(['theme_id' => $themeId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Search and return themes list as ActiveDataProvider
     * for authorized shop
     *
     * @param $userId
     * @return ActiveDataProvider
     */
    public function searchThemes($userId)
    {
        $query = ThemeEntity::find()
            ->select('id,name,image,date_of_publication,user_id,new_comments_count')
            ->where(['user_id' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'new_comments_count'  => SORT_DESC,
                    'date_of_publication' => SORT_DESC,
                ],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Search and return current theme data
     *
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function searchCurrentTheme($id)
    {
        return ThemeEntity::find()
            ->select('id, name, date_of_publication, user_id')
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Getting avatar for shop or user
     *
     * @param $id integer
     * @return bool|string
     */
    public function getAvatar($id)
    {
        $defaultImage = Yii::$app->assetManager->getPublishedUrl('@bower'). '/admin-lte/img/avatar5.png';

        /** If commentator has Shop role output shop image */
        if (isset(Yii::$app->authManager->getRolesByUser($id)['shop'])) {
            $image = ShopProfileEntity::find()->where(['user_id' => $id])->one()['image'];
            return (!empty($image)) ? $image : $defaultImage;
        }

        /* Or return default user img*/
        return $defaultImage;
    }

    /**
     * Getting authorized shop name
     *
     * @param $userId
     * @return mixed|string
     */
    public function getShopName($userId)
    {
        $shop = ShopProfileEntity::find()
            ->where(['user_id' => $userId])
            ->one();

        return $shop;
    }

    /**
     * Getting user email or shop name for output in
     * index view
     *
     * @param $id
     * @return mixed|string
     */
    public function getUserName($id)
    {
        if (isset(Yii::$app->authManager->getRolesByUser($id)['shop'])) {
            $user = $this->getShopName($id)->name;
            return $user;
        }

        $user = UserEntity::find()
            ->where(['id' => $id])
            ->one();
        return $user['email'];
    }

    /**
     * Updating comments status when authorized shop comes to
     * theme with unread commentaries and updating "Theme" new
     * comments counter
     *
     * @param $themeId
     */
    public static function updateCommentsStatus($themeId)
    {
        $models = CommentEntity::findAll(['theme_id' => $themeId]);
        /* @var $model CommentEntity */
        foreach ($models as $model) {
            if ($model->status == CommentEntity::STATUS_UNREAD) {
                $model->scenario = CommentEntity::SCENARIO_DEFAULT;
                $model->status = CommentEntity::STATUS_READ;
                $model->save();
            }
        }

        $theme = ThemeEntity::find()->where(['id' => $themeId])->one();
        /* @var $theme ThemeEntity */
        if ($theme->new_comments_count > 0) {
            $theme->scenario = ThemeEntity::SCENARIO_DEFAULT;
            $theme->new_comments_count = 0;
            $theme->save();
        }
    }

    /**
     * Method of getting total count comments for all shop themes
     *
     * @param $shopId
     * @return integer
     */
    public function getTotalCountCommentByShop($shopId)
    {
        $ids = ArrayHelper::getColumn(ThemeEntity::find()->select('id')
            ->where(['user_id' => $shopId])->asArray()->all(), 'id');

        return CommentEntity::find()->where(['in', 'theme_id', $ids])->count();
    }

    /**
     * Method of getting count all user comments or count new comments for his themes
     *
     * @param $byTheme
     * @return int
     */
    public function getCountComments($byTheme = false): int
    {
        if ($byTheme) {
            $ids = ArrayHelper::getColumn(ThemeEntity::find()->select('id')
                ->where(['user_id' => Yii::$app->user->identity->getId()])->asArray()->all(), 'id');

            return CommentEntity::find()->where(['in', 'theme_id', $ids, 'status' => CommentEntity::STATUS_UNREAD])
                ->count();
        } else {
            return CommentEntity::find()->where(['created_by' => Yii::$app->user->identity->getId()])->count();
        }
    }

    /**
     * Method of getting count all user comments or count new comments for his themes
     *
     * @param $categoryId
     * @return int
     */
    public function getCountChildCategoryComments($categoryId): int
    {
        return CommentEntity::find()
            ->leftJoin('theme', 'theme.id = comment.theme_id')
            ->where(['theme.category_id' => $categoryId])
            ->count();
    }
}