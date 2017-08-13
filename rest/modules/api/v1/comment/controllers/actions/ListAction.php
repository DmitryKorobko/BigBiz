<?php

namespace rest\modules\api\v1\comment\controllers\actions;

use common\behaviors\{
    ValidateGetParameters, AccessUserBehavior
};
use common\models\comment\CommentEntity;
use yii\rest\Action;
use Yii;

/**
 * Class ListAction
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserBehavior
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class ListAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['theme_id']
            ],
            [
                'class'   => AccessUserBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /** Method of getting list comments of theme  */
    public function run()
    {
        /** @var  $comment CommentEntity*/
        $comment = new $this->modelClass();

        return $comment->getComments(Yii::$app->request->getQueryParams()['theme_id']);
    }

}