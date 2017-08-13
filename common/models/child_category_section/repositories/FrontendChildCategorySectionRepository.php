<?php
namespace common\models\child_category_section\repositories;

use common\models\{
    child_category_section\ChildCategorySectionEntity, comment\CommentEntity, theme\ThemeEntity
};

/**
 * Class FrontendChildCategorySectionRepository
 * @package common\models\child_category_section\repositories
 */
trait FrontendChildCategorySectionRepository
{
    /**
     * Method of getting count all themes
     *
     * @return int
     */
    public function getCountAllThemes(): int
    {
        return ChildCategorySectionEntity::find()->count();
    }

    /**
     * Method of getting list of child categories with main information by main category id
     *
     * @param $mainCategoryId
     * @return array
     */
    public function getListChildCategoriesByMain($mainCategoryId): array
    {
        $model = ChildCategorySectionEntity::find()
            ->select(['child_category_section.id', 'name', 'description'])
            ->where(['child_category_section.parent_category_id' => $mainCategoryId])
            ->asArray()
            ->all();

        $categories = [];
        /** @var  $comment  CommentEntity*/
        $comment = new CommentEntity();
        /** @var  $theme  ThemeEntity*/
        $theme = new ThemeEntity();

        foreach ($model as $category) {
            $categories[] = [
                'id'                => $category['id'],
                'name'              => $category['name'],
                'description'       => $category['description'],
                'count_of_themes'   => $theme->getCountCategoryThemes($category['id']),
                'count_of_comments' => $comment->getCountChildCategoryComments($category['id'])
            ];
        }

        return $categories;
    }
}