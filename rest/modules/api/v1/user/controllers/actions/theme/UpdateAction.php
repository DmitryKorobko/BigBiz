<?php

namespace rest\modules\api\v1\user\controllers\actions\theme;

use common\{
    behaviors\ValidatePostParameters, behaviors\ValidationExceptionFirstMessage, models\theme\ThemeEntity,
    behaviors\AccessUserStatusBehavior, models\child_category_section\ChildCategorySectionEntity
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException, web\HttpException
};

/**
 * Class Update Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\user\controllers\actions\theme
 */
class UpdateAction extends Action
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
    protected function beforeRun()
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of updating theme by user
     *
     * @param $id
     * @return ThemeEntity
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws HttpException
     */
    public function run($id)
    {
        /**
         * @var $themeModel ThemeEntity.php
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

        $themeModel = ThemeEntity::findOne(['id' => $id, 'user_id' => Yii::$app->user->identity->getId()]);

        if (!$themeModel) {
            throw new NotFoundHttpException('Тема не найдена.');
        }

        $themeModel->scenario = ThemeEntity::SCENARIO_UPDATE;
        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['user_id'] = Yii::$app->user->identity->getId();
        $postData['status'] = ThemeEntity::STATUS_UNVERIFIED;
        $postData['category_id'] = (int) $postData['category_id'];
        $themeModel->load($postData, '');

        if ($themeModel->save()) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Тема успешно изменена',
                'data'    => $themeModel->getAttributes()
            ];
        } elseif ($themeModel->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($themeModel->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
            ошибке администарации приложения.');
    }
}
