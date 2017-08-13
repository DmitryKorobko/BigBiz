<?php

namespace rest\modules\api\v1\user\controllers\actions\theme;

use common\{
    behaviors\ValidationExceptionFirstMessage, models\child_category_section\ChildCategorySectionEntity,
    models\theme\ThemeEntity, behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException, web\HttpException
};

/**
 * Class Create Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\user\controllers\actions\theme
 */
class CreateAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['category_id', 'name', 'description']
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Add theme by user
     *
     * @return array|\yii\db\ActiveRecord
     * @throws ServerErrorHttpException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        /**
         * @var $model ThemeEntity.php
         * @var $category ChildCategorySectionEntity.php
         */
        $category = ChildCategorySectionEntity::findOne(['id' => Yii::$app->getRequest()
            ->getBodyParams()['category_id']]);

        if (!$category) {
            throw new NotFoundHttpException('Категория не найдена.');
        }

        if ($category->permissions_only_admin !== 0) {
            throw new HttpException(403, 'Доступ запрещён.');
        }

        /** @var  $model ThemeEntity */
        $model = new $this->modelClass();
        $model->scenario = ThemeEntity::SCENARIO_CREATE;
        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['user_id'] = Yii::$app->user->identity->getId();
        $model->load($postData, '');
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201, 'Created');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Тема успешно создана',
                'data'    => $model->getAttributes()
            ];
        } elseif ($model->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($model->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
            ошибке администарации приложения.');
    }
}
