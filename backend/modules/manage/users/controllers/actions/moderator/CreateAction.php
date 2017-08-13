<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use backend\models\BackendUserEntity;
use common\models\user\UserEntity;
use Yii;
use yii\base\Action;

/**
 * Class CreateAction
 *
 * @package backend\modules\manage\users\controllers\actions\moderator
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/manage/users/views/moderator/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * Creates a new moder user.
     *
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function run()
    {
        /** @var  $model BackendUserEntity */
        $model = new BackendUserEntity();
        $model->scenario = BackendUserEntity::SCENARIO_CREATE;
        if (isset(Yii::$app->request->post()['BackendUserEntity'])) {

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->refresh_token = base64_encode(md5(time()) . md5(rand(1000, 9999)));

                if ($model->save()) {
                    $moder = UserEntity::findOne(['email' => $model->email]);
                    $moder->scenario = UserEntity::SCENARIO_UPDATE;
                    $moder->setAttribute('status', UserEntity::STATUS_VERIFIED);

                    if ($moder->save()){
                        Yii::$app->getSession()->setFlash('success', "Модератор `{$model->email}` создан успешно!");
                        return $this->controller->redirect('index');
                    }
                }

                Yii::$app->getSession()->setFlash('error',
                    "Произошла ошибка при добавлении модератора - {$model->email}!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, ['model' => $model]);
    }
}