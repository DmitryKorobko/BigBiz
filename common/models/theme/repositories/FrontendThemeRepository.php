<?php
namespace common\models\theme\repositories;

use common\models\{
    theme\ThemeEntity, user\UserEntity, user_profile\UserProfileEntity, shop_profile\ShopProfileEntity,
    theme_user_like_show\ThemeUserLikeShowEntity
};
use Yii;
use yii\data\ArrayDataProvider;

/**
 * Class FrontendThemeRepository
 * @package common\models\theme\repositories
 */
trait FrontendThemeRepository
{
    /**
     * Method of getting count all themes
     *
     * @return int
     */
    public function getCountAllThemes(): int
    {
        return ThemeEntity::find()
            ->leftJoin('user', 'user.id = theme.user_id')
            ->where(['theme.status' => ThemeEntity::STATUS_VERIFIED, 'user.status' => UserEntity::STATUS_VERIFIED])
            ->count();
    }

    /**
     * Method of getting count of themes in category
     *
     * @param integer $categoryId Id of category
     * @return int
     */
    public function getCountCategoryThemes($categoryId): int
    {
        return ThemeEntity::find()
            ->leftJoin('user', 'user.id = theme.user_id')
            ->where(['category_id' => $categoryId, 'theme.status' => ThemeEntity::STATUS_VERIFIED,
                'user.status' => UserEntity::STATUS_VERIFIED])
            ->count();
    }

    /**
     * Method of getting list themes
     *
     * @param integer $categoryId Id of category
     * @param integer $shopId Id of user(shop)
     * @param bool $asArray If result must be array
     * @param integer $start Number of first element of result array in main array
     * @param bool $dateFormat If date in result must be ('d.m.y') format
     * @return ArrayDataProvider | array
     */
    public function getListThemesByCategoryOrShop($categoryId = null, $shopId = null, $asArray = false, $start = null,
                                                  $dateFormat = false)
    {
        $themes = [];
        $query = ThemeEntity::find()
            ->select(['theme.id', 'theme.name', 'theme.view_count', 'theme.status',
                'theme.image', 'child_category_section.name as category_name', 'theme.category_id',
                'theme.comments_count', 'theme.date_of_publication', 'theme.user_id',
                'main_category_section.name as main_category_name',
                'child_category_section.id as category_id'])
            ->joinWith('category')
            ->leftJoin('main_category_section',
                'main_category_section.id = child_category_section.parent_category_id');

        if (!empty($categoryId)) {
            $query->where(['theme.category_id' => $categoryId, 'theme.status' => ThemeEntity::STATUS_VERIFIED]);
        }

        if (!empty($shopId)) {
            $query->leftJoin('shop_profile', 'theme.user_id = shop_profile.user_id')
                ->where(['theme.user_id' => $shopId, 'theme.status' => ThemeEntity::STATUS_VERIFIED]);
        }

        $models = $query->orderBy(['theme.date_of_publication' => SORT_DESC])->asArray()->all();

        if ($models) {
            foreach ($models as $theme) {
                $themeWithoutUserName = [
                    'id'                  => $theme['id'],
                    'name'                => $theme['name'],
                    'category_name'       => $theme['category_name'],
                    'image'               => (!empty($theme['image'])) ? $theme['image'] : '/img/article/no-pic.png',
                    'view_count'          => $theme['view_count'],
                    'comments_count'      => $theme['comments_count'],
                    'date_of_publication' => $theme['date_of_publication'],
                    'count_like'          => ThemeUserLikeShowEntity::find()
                                                 ->where(['theme_id' => $theme['id'], 'like' => 1])->count(),
                    'count_dislike'       => ThemeUserLikeShowEntity::find()
                                                 ->where(['theme_id' => $theme['id'], 'like' => 0])->count(),
                    'main_category_name'  => $theme['main_category_name'],
                    'category_id'         => $theme['category_id']
                ];

                if ($dateFormat){
                    $themeWithoutUserName['date_of_publication'] = date('d.m.y', $theme['date_of_publication']);
                }

                /**
                 * @var  $shop ShopProfileEntity
                 * @var  $user UserProfileEntity
                 * @var  $userModel UserEntity
                 */
                $shop = ShopProfileEntity::findOne(['user_id' => $theme['user_id']]);
                $user = UserProfileEntity::findOne(['user_id' => $theme['user_id']]);
                $userModel = UserEntity::findOne(['id' => $theme['user_id']]);
                $assignmentAdmin = Yii::$app->authManager->getAssignment(UserEntity::ROLE_ADMIN,
                    $userModel->getId());
                $assignmentModer = Yii::$app->authManager->getAssignment(UserEntity::ROLE_MODER,
                    $userModel->getId());


                if ($shop) {
                    $themeWithoutUserName['user_name'] = $shop->name;
                } else {
                    if (($assignmentAdmin && $assignmentAdmin->roleName == UserEntity::ROLE_ADMIN)
                        || ($assignmentModer && $assignmentModer->roleName == UserEntity::ROLE_MODER)) {
                        $themeWithoutUserName['user_name'] = 'Администрация';
                    } else {
                        $themeWithoutUserName['user_name'] = $user->nickname;
                    }
                }

                if ($userModel->status == UserEntity::STATUS_VERIFIED && $userModel->is_deleted == 0) {
                    $themes[] = $themeWithoutUserName;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $themes,
            'pagination' => [
                'pageSize' => Yii::$app->params['themesPerPage']
            ]
        ]);

        if ($asArray) {
            return array_slice($themes, $start, Yii::$app->params['themesPerPage']);
        }

        return $dataProvider;
    }
}