<?php

namespace rest\modules\api\v1\content\controllers\actions\category;

use Yii;
use Yii\data\ActiveDataProvider;
use yii\rest\Action;
use common\models\main_category_section\MainCategorySectionEntity;

/**
 * Class ListMain Action
 *
 * @package rest\modules\api\v1\content\controllers\actions\category
 */
class ListMainAction extends Action
{
    /**
     * Action of getting list of main categories
     *
     */
    public function run(): ActiveDataProvider
    {
        /** @var  $category MainCategorySectionEntity.php */
        $category = new $this->modelClass;

        return $category->search(Yii::$app->request->queryParams);
    }
}
