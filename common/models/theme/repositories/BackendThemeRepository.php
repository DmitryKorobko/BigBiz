<?php
namespace common\models\theme\repositories;

use common\models\{
    shop_profile\ShopProfileEntity, theme\ThemeEntity, user_profile\UserProfileEntity,
    child_category_section\ChildCategorySectionEntity
};
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class BackendThemeRepository
 * @package common\models\theme\repositories
 */
trait BackendThemeRepository
{
    /**
     * Gets all data from table and returns dataProvider
     *
     * @param array $params
     * @param bool $allThemes
     *
     * @return ActiveDataProvider
     */
    public function search($params, $allThemes = false)
    {
        $shopId = isset($params['shop_id']) ? $params['shop_id'] : Yii::$app->user->identity->getId();
        $themeId = isset($params['theme_id']) ? $params['theme_id'] : null;
        if (isset($themeId)) {
            $query = ThemeEntity::find()
                ->leftJoin('user_profile', 'user_profile.user_id = theme.user_id')
                ->leftJoin('shop_profile', 'shop_profile.user_id = theme.user_id')
                ->where(['theme.id' => $themeId]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 1
                ]
            ]);
        } else {
            if ($allThemes) {
                $query = ThemeEntity::find()->orderBy('sort asc')
                    ->leftJoin('user_profile', 'user_profile.user_id = theme.user_id')
                    ->leftJoin('shop_profile', 'shop_profile.user_id = theme.user_id');
            } else {
                $query = ThemeEntity::find()->where(['theme.user_id' => $shopId])->orderBy('sort asc');
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => isset($params['limit']) ? $params['limit'] : 10
                ]
            ]);
        }

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'status',
                'sort',
                'comments_count',
                'view_count',
                'created_at',
                'user_id',
                'category_id',
                'image'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($params['ThemeEntity'])) {
            $query
                ->andFilterWhere(['=', 'category_id', $params['ThemeEntity']['category_id']])
                ->andFilterWhere(['like', 'theme.name', $params['ThemeEntity']['name']])
                ->andFilterWhere(['=', 'status', $params['ThemeEntity']['status']]);
        }

        if ($this->user_name) {
            $query
                ->andFilterWhere(['like', 'user_profile.nickname', $this->user_name])
                ->orFilterWhere(['like', 'shop_profile.name', $this->user_name]);
        }

        if (!empty($this->theme_created_range) && strpos($this->theme_created_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->theme_created_range);
            $query->andFilterWhere(['between', 'created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }

    /**
     * Getting theme image or default image
     *
     * @param $themeId
     * @return bool|string
     */
    public function getThemeImage($themeId)
    {
        $defaultImage = Yii::getAlias('@web/images/default/no_image.png');

        $themeImage = ThemeEntity::find()->where(['id' => $themeId])->one()['image'];
        $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $themeImage);
        if (!empty($themeImage) && file_exists($imagePath)) {
            return $themeImage;
        }

        return $defaultImage;
    }

    /**
     * Method of creating few models for saving many to many relationships
     *
     * @param $ids
     * @return array
     */
    public static function createMultipleModels($ids)
    {
        $result = [];
        if ($ids) {
            foreach ($ids as $id) {
                $result[] = ThemeEntity::findOne($id);
            }
        }
        return $result;
    }

    /**
     * Method of editing theme status
     *
     * @param $status
     * @return bool
     */
    public function changeThemeStatus($status)
    {
        $this->status = $status;
        if ($this->save(false)) {
            return true;
        }

        return false;
    }

    /**
     * Method of getting count user themes
     *
     * @return int
     */
    public function getCountOwnThemes(): int
    {
        return ThemeEntity::find()->where(['user_id' => Yii::$app->user->identity->getId()])->count();
    }

    /**
     * Method of getting theme creator name
     *
     * @param $userId
     * @return string
     */
    public function getThemeCreatorName($userId): string
    {
        $user = (UserProfileEntity::find()->where(['user_id' => $userId])->asArray()->one())['nickname'];

        if (!isset($user)) {
            $user = (ShopProfileEntity::find()->where(['user_id' => $userId])->asArray()->one())['name'];
        }

        if (!isset($user)) {
            $user = 'Администрация';
        }

        return $user;
    }

    /**
     * Method of getting count new messages for current authorized user
     *
     * @return int
     */
    public function getCountNewThemesForAdmin(): int
    {
        $ids = ChildCategorySectionEntity::find()->select('id')
            ->where('name = "Арбитраж" OR name = "Частные продавцы"');
        return (int) ThemeEntity::find()
            ->where(['theme.category_id' => $ids, 'theme.status' => ThemeEntity::STATUS_UNVERIFIED])
            ->count();
    }
}