<?php
namespace common\models\main_category_section\repositories;

use common\models\{
    child_category_section\ChildCategorySectionEntity, main_category_section\MainCategorySectionEntity
};

/**
 * Class FrontendMainCategorySectionRepository
 * @package common\models\main_category_section\repositories
 */
trait FrontendMainCategorySectionRepository
{
    /**
     * Method of getting list of main categories with child categories
     *
     * @return array
     */
    public function getListMainCategories(): array
    {
        $model = MainCategorySectionEntity::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        $categories = [];

        $childCategory = new ChildCategorySectionEntity();

        foreach ($model as $category) {
            $categories[] = [
                'name'             => $category['name'],
                'child_categories' => $childCategory->getListChildCategoriesByMain($category['id'])
            ];
        }

        return $categories;
    }
}