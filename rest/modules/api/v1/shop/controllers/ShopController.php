<?php
namespace rest\modules\api\v1\shop\controllers;

use common\models\{
    answer\AnswerEntity, shop_profile\ShopProfileEntity
};
use rest\modules\api\v1\shop\controllers\actions\shop\{
    PreviewAction, ProfileAction, AnswersAction, SideMenuAction, DetailAction
};
use yii\{
    data\ArrayDataProvider, filters\auth\HttpBearerAuth, rest\ActiveController
};

/**
 * Class ShopController
 *
 * @package rest\modules\api\v1\shop\controllers
 */
class ShopController extends ActiveController
{
    /** @var  $modelClass ShopProfileEntity.php */
    public $modelClass = ShopProfileEntity::class;

    /**
     * @var array
     */
    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];

        $actions['preview'] = [
            'class'      => PreviewAction::class,
            'modelClass' => ShopProfileEntity::class
        ];

        $actions['profile'] = [
            'class'      => ProfileAction::class,
            'modelClass' => ShopProfileEntity::class
        ];

        $actions['answers'] = [
            'class'      => AnswersAction::class,
            'modelClass' => AnswerEntity::class
        ];

        $actions['side-menu'] = [
            'class'      => SideMenuAction::class,
            'modelClass' => ShopProfileEntity::class
        ];

        $actions['detail'] = [
            'class'      => DetailAction::class,
            'modelClass' => ShopProfileEntity::class
        ];

        return $actions;
    }

    /**
     * @return \yii\data\ArrayDataProvider
     */
    public function indexDataProvider(): ArrayDataProvider
    {
        /** @var  $searchModel ShopProfileEntity.php */
        $searchModel = new $this->modelClass;
        return $searchModel->getListShops(\Yii::$app->request->queryParams);
    }
}
