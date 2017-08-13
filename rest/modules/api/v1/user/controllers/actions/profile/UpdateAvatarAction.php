<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\{
    behaviors\ValidatePostParameters, behaviors\ValidationExceptionFirstMessage, models\user_profile\UserProfileEntity,
    behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class UpdateAvatarAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdateAvatarAction extends Action
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
                'inputParams' => ['base64_image']
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
        $this->checkUserRole();
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of updating theme by user
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run(): array
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        /** @var  $userProfileModel UserProfileEntity */
        $userProfileModel = UserProfileEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);
        if (!$userProfileModel) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }
        $userProfileModel->scenario = UserProfileEntity::SCENARIO_UPDATE;

        $postData = Yii::$app->getRequest()->getBodyParams();
        $postData['user_id'] = Yii::$app->user->identity->getId();

        if (isset($postData['base64_image'])) {
            $fileName = Yii::$app->params['s3_folders']['user_profile'] . '/user-' . Yii::$app->user->identity->getId()
                . '/' . Yii::$app->security->generateRandomString() . '.' . 'jpeg';
            $data = explode(',', $postData['base64_image']);

            if (!isset($data[1])){
                throw new ServerErrorHttpException('Некорректный формат изображения');
            }

            $result = $s3->commands()->put($fileName, base64_decode($data[1]))
                ->withContentType("image/jpeg")->execute();
            $postData['avatar'] = $result->get('ObjectURL');
            unset($postData['base64_image']);
        }

        $userProfileModel->load($postData, '');
        if ($userProfileModel->save()) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Аватар успешно изменён',
                'data'    => $userProfileModel->getProfileInformation(Yii::$app->user->identity->getId())
            ];
        } elseif ($userProfileModel->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($userProfileModel->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об 
            ошибке администарации приложения.');
    }
}
