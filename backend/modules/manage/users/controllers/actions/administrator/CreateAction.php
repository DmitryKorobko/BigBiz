<?php
namespace backend\modules\manage\users\controllers\actions\administrator;

use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;
use common\models\user\UserEntity;

/**
 * Class CreateAction
 *
 * @package backend\modules\manage\users\controllers\actions\administrator
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/manage/users/views/administrator/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * Creates a new admin user.
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
                    $admin = UserEntity::findOne(['email' => $model->email]);
                    $admin->scenario = UserEntity::SCENARIO_UPDATE;
                    $admin->setAttribute('status', UserEntity::STATUS_VERIFIED);

                    if ($admin->save()) {
                        Yii::$app->getSession()->setFlash('success', "Администратор `{$model->email}` создан успешно!");
                        return $this->controller->redirect('index');
                    }
                }

                Yii::$app->getSession()->setFlash('error',
                    "Произошла ошибка при добавлении администратора - {$model->email}!");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, ['model' => $model]);
    }
}