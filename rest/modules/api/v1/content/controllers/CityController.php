<?php

namespace rest\modules\api\v1\content\controllers;

use common\models\city\CityEntity;
use yii\{
    filters\auth\HttpBearerAuth, rest\ActiveController
};

/**
 * Class CityController
 *
 * @package rest\modules\api\v1\content\controllers
 */
class CityController extends ActiveController
{
    /** @var  $modelClass CityEntity.php */
    public $modelClass = CityEntity::class;

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
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];

        return $actions;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function indexDataProvider()
    {
        /** @var  $searchModel CityEntity.php */
        $searchModel = new $this->modelClass;

        return $searchModel->find()->orderBy('name')->all();
    }

    /**
     * @inheritdoc
     */
    protected function verbs(): array
    {
        return [
            'index' => ['GET']
        ];
    }
}
