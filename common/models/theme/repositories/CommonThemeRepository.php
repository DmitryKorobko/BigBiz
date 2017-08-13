<?php
namespace common\models\theme\repositories;

use common\models\{
    user_theme_favorite\UserThemeFavoriteEntity, theme\ThemeEntity, child_category_section\ChildCategorySectionEntity,
    theme_user_like_show\ThemeUserLikeShowEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity
};
use yii\data\ArrayDataProvider;
use rest\models\RestUser;
use Yii;


/**
 * Class CommonThemeRepository
 * @package common\models\theme\repositories
 */
trait CommonThemeRepository
{
    /**
     * Method of checking theme is favorite or not by userID
     *
     * @param $themeId
     * @param $userId
     * @return bool
     */
    public function isFavoriteThemeByUser($themeId, $userId): bool
    {
        if (UserThemeFavoriteEntity::find()->where(['theme_id' => $themeId, 'user_id' => $userId])->one()) {
            return true;
        }
        return false;
    }

    /**
     * Method of getting count of themes of shop
     *
     * @param integer $userId Id of user
     * @return int
     */
    public function getCountShopThemes($userId): int
    {
        return ThemeEntity::find()->where(['user_id' => $userId])->count();
    }

    /**
     * Method of getting list themes
     *
     * @param $params
     * @param $newThemesForAdmin
     * @return ArrayDataProvider | array
     */
    public function getListCategoryThemes($params, $newThemesForAdmin = false)
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        $themes = [];
        $query = ThemeEntity::find()
            ->select(['theme.id', 'theme.name', 'theme.view_count', 'theme.status',
                'theme.image', 'child_category_section.name as category_name', 'theme.category_id',
                'theme.comments_count', 'theme.date_of_publication', 'theme.user_id'])
            ->joinWith('category');


        if ($newThemesForAdmin) {
            $ids = ChildCategorySectionEntity::find()->select('id')
                ->where('name = "Арбитраж" OR name = "Частные продавцы"');
            $models = $query->where(['theme.category_id' => $ids, 'theme.status' => ThemeEntity::STATUS_UNVERIFIED])
                ->orderBy(['theme.sort' => SORT_ASC])
                ->asArray()
                ->all();
        } else {
            $models = $query->where(['theme.category_id' => $params['category_id'], 'theme.status' => ThemeEntity::STATUS_VERIFIED])
                ->orderBy(['theme.sort' => SORT_ASC])
                ->asArray()
                ->all();
        }

        if ($models) {
            foreach ($models as $theme) {
                $likeThisTheme = ThemeUserLikeShowEntity::find()
                    ->where(['theme_id' => $theme['id'], 'user_id' => $userId])->asArray()->one();
                $themeWithoutUserName = [
                    'id'                  => (int) $theme['id'],
                    'name'                => $theme['name'],
                    'category_name'       => $theme['category_name'],
                    'image'               => $theme['image'],
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
                    $themeWithoutUserName['user_avatar'] = $shop->image;
                } else {
                    if ($assignment && $assignment->roleName === 'admin') {
                        $themeWithoutUserName['user_name'] = 'Администрация';
                    } else {
                        $themeWithoutUserName['user_name'] = $user->nickname;
                        $themeWithoutUserName['user_avatar'] = $user->avatar;
                    }
                }

                $themes[] = $themeWithoutUserName;
            }
        }

        if ($newThemesForAdmin) {
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
}