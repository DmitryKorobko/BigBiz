<?php
namespace common\models\theme\repositories;

use Yii;
use rest\models\RestUser;
use yii\data\ArrayDataProvider;
use common\models\{
    theme\ThemeEntity, theme_user_like_show\ThemeUserLikeShowEntity, shop_profile\ShopProfileEntity,
    user_profile\UserProfileEntity
};

/**
 * Class RestThemeRepository
 *
 * @package common\models\theme\repositories
 */
trait RestThemeRepository
{
    /**
     * Method of getting details of theme
     *
     * @param $params
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getThemeDetail($params)
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        /** @var  $theme ThemeEntity*/
        $theme = ThemeEntity::find()
            ->select(['theme.id', 'theme.name', 'theme.description', 'theme.view_count',
                'theme.comments_count', 'theme.image', 'theme.date_of_publication', 'theme.status',
                'child_category_section.name as category_name', 'theme.category_id', 'theme.user_id'])
            ->joinWith('category')
            ->where(['theme.id' => $params['id'], 'theme.status' => ThemeEntity::STATUS_VERIFIED])
            ->asArray()
            ->one();

        if ($theme) {
            $likeThisTheme = ThemeUserLikeShowEntity::find()
                ->where(['theme_id' => $theme['id'], 'user_id' => $userId])->asArray()->one();
            $theme = [
                'id'                  => (int) $theme['id'],
                'user_id'             => (int) $theme['user_id'],
                'category_name'       => $theme['category_name'],
                'name'                => $theme['name'],
                'description'         => strip_tags($theme['description']),
                'image'               => $theme['image'],
                'view_count'          => (int) $theme['view_count'],
                'comments_count'      => (int) $theme['comments_count'],
                'date_of_publication' => (int) $theme['date_of_publication'],
                'is_favorite'         => $this->isFavoriteThemeByUser($theme['id'], Yii::$app->user->identity->getId()),
                'count_like'          => (int) ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'like' => 1])->count(),
                'count_dislike'       => (int) ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'like' => 0])->count(),
                'is_like'             => (isset($likeThisTheme) && $likeThisTheme['like'] == 1) ? true : false,
                'is_dislike'          => (isset($likeThisTheme) && $likeThisTheme['like'] == 0) ? true : false
            ];

            /**
             * @var  $shop ShopProfileEntity
             * @var  $user UserProfileEntity
             * @var  $userModel RestUser
             */
            $shop = ShopProfileEntity::findOne(['user_id' => $theme['user_id']]);
            $user = UserProfileEntity::findOne(['user_id' => $theme['user_id']]);
            $userModel = RestUser::findOne(['id' => $theme['user_id']]);
            $assignment = Yii::$app->authManager->getAssignment('admin', $userModel->getId());
            unset($theme['user_id']);

            if ($shop) {
                $theme['user_name'] = $shop->name;
            } else {
                if ($assignment && $assignment->roleName === 'admin') {
                    $theme['user_name'] = 'Администрация';
                } else {
                    $theme['user_name'] = $user->nickname;
                }
            }
        }

        return $theme;
    }

    /**
     * Method of getting themes in favorites by userID
     *
     * @param bool $limit
     * @return array|ArrayDataProvider
     */
    public function getFavoritesThemesByUser($limit = false)
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        $query = ThemeEntity::find()
            ->select(['theme.id', 'theme.name', 'theme.view_count', 'child_category_section.name as category_name',
                'theme.comments_count', 'theme.date_of_publication', 'theme.user_id'
            ])
            ->leftJoin('child_category_section', 'child_category_section.id = theme.category_id')
            ->leftJoin('user_theme_favorite', 'user_theme_favorite.theme_id = theme.id')
            ->where(['user_theme_favorite.user_id' => Yii::$app->user->identity->getId()]);

        if ($limit) {
            $models = $query->limit($limit)->asArray()->all();
        } else {
            $models =  $query->asArray()->all();
        }

        $themes = [];
        if ($models) {
            foreach ($models as $theme) {
                $likeThisTheme = ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'user_id' => $userId])->asArray()->one();
                $themeWithoutUserName = [
                    'name'                => $theme['name'],
                    'category_name'       => $theme['category_name'],
                    'view_count'          => (int) $theme['view_count'],
                    'comments_count'      => (int) $theme['comments_count'],
                    'date_of_publication' => (int) $theme['date_of_publication'],
                    'count_like'          => (int) ThemeUserLikeShowEntity::find()
                        ->where(['theme_id' => $theme['id'], 'like' => 1])->count(),
                    'count_dislike'       => (int) ThemeUserLikeShowEntity::find()
                        ->where(['theme_id' => $theme['id'], 'like' => 0])->count(),
                    'is_like'             => (isset($likeThisTheme) && $likeThisTheme['like'] == 1) ? 1 : 0,
                    'is_dislike'          => (isset($likeThisTheme) && $likeThisTheme['like'] == 0) ? 1 : 0
                ];

                /**
                 * @var  $shop ShopProfileEntity
                 * @var  $user UserProfileEntity
                 * @var  $userModel RestUser
                 */
                $shop = ShopProfileEntity::findOne(['user_id' => $theme['user_id']]);
                $user = UserProfileEntity::findOne(['user_id' => $theme['user_id']]);
                $userModel = RestUser::findOne(['id' => $theme['user_id']]);
                $assignment = Yii::$app->authManager->getAssignment('admin', $userModel->getId());

                if ($shop) {
                    $themeWithoutUserName['user_name'] = $shop->name;
                } else {
                    if ($assignment && $assignment->roleName === 'admin') {
                        $themeWithoutUserName['user_name'] = 'Администрация';
                    } else {
                        $themeWithoutUserName['user_name'] = $user->nickname;
                    }
                }

                $themes[] = $themeWithoutUserName;
            }
        }

        if ($limit) {
            return $themes;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $themes,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Method of getting list shop themes
     *
     * @param $userId
     * @param $limit
     * @return array|ArrayDataProvider
     */
    public function getListShopThemes($userId, $limit = false)
    {
        $currentUserId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        $query = ThemeEntity::find()
            ->select(['theme.id', 'theme.name', 'theme.view_count', 'child_category_section.name as category_name',
                'theme.category_id', 'shop_profile.name as shop_name', 'theme.comments_count',
                'theme.date_of_publication', 'theme.user_id'])
            ->joinWith('category')
            ->leftJoin('shop_profile', 'shop_profile.user_id = theme.user_id')
            ->where(['theme.user_id' => $userId, 'theme.status' => ThemeEntity::STATUS_VERIFIED])
            ->orderBy(['theme.sort' => 'asc']);

        if ($limit) {
            $models = $query->limit($limit)->asArray()->all();
        } else {
            $models = $query->asArray()->all();
        }

        $themes = [];
        if ($models) {
            foreach ($models as $theme) {
                $likeThisTheme = ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'user_id' => $currentUserId])->asArray()->one();
                $themes[] = [
                    'id'                  => (int) $theme['id'],
                    'category_name'       => $theme['category_name'],
                    'name'                => $theme['name'],
                    'shop_name'           => $theme['shop_name'],
                    'view_count'          => (int) $theme['view_count'],
                    'comments_count'      => (int) $theme['comments_count'],
                    'date_of_publication' => (int) $theme['date_of_publication'],
                    'count_like'          => (int) ThemeUserLikeShowEntity::find()
                        ->where(['theme_id' => $theme['id'], 'like' => 1])->count(),
                    'count_dislike'       => (int) ThemeUserLikeShowEntity::find()
                        ->where(['theme_id' => $theme['id'], 'like' => 0])->count(),
                    'is_favorite'         => $this->isFavoriteThemeByUser($theme['id'],
                        (!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest'),
                    'is_like'             => (isset($likeThisTheme) && $likeThisTheme['like'] == 1) ? 1 : 0,
                    'is_dislike'          => (isset($likeThisTheme) && $likeThisTheme['like'] == 0) ? 1 : 0
                ];
            }
        }

        if ($limit) {
            return $themes;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $themes,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return $dataProvider;
    }

    /**
     * Method of getting list user themes
     *
     * @return ArrayDataProvider
     */
    public function getListUserThemes(): ArrayDataProvider
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        $models = ThemeEntity::find()
            ->select(['theme.id', 'theme.name', 'theme.view_count', 'theme.status', 'theme.image',
                'child_category_section.name as category_name', 'theme.category_id',
                'theme.comments_count', 'theme.date_of_publication'])
            ->joinWith('category')
            ->where(['theme.user_id' => $userId])
            ->orderBy(['theme.created_at' => 'desc'])
            ->asArray()
            ->all();

        $themes = [];
        if ($models) {
            foreach ($models as $theme) {
                $likeThisTheme = ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'user_id' => $userId])->asArray()->one();
                unset($theme['category']);
                $theme['count_like']    = (int) ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'like' => 1])->count();

                $theme['count_dislike'] = (int) ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'like' => 0])->count();
                $theme['is_like']       = (isset($likeThisTheme) && $likeThisTheme['like'] == 1) ? 1 : 0;
                $theme['is_dislike']    = (isset($likeThisTheme) && $likeThisTheme['like'] == 0) ? 1 : 0;
                $themes[] = $theme;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $themes,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Method of increasing view count of theme param
     *
     * @param $themeId
     */
    public function increaseViewCountTheme($themeId)
    {
        $theme = ThemeEntity::findOne(['id' => $themeId]);
        $theme->view_count = ++$theme->view_count;
        $theme->save(false);
    }

}