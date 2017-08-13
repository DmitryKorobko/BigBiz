<?php

namespace rest\modules\api\v1\comment\controllers\actions;

use common\behaviors\ValidateGetParameters;
use common\models\comment\CommentEntity;
use yii\rest\Action;

/**
 * Class Index Action
 *
 * @mixin ValidateGetParameters
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class IndexAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => [
                    'theme_id'
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting list comments of theme
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var  $model CommentEntity.php */
        $model = new $this->modelClass;

        return $model->getListComments(\Yii::$app->request->queryParams['theme_id']);
    }
}
