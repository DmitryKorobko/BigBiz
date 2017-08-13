<?php
namespace common\models\user_reputation\repositories;

use common\models\user_profile\UserProfileEntity;
use Yii;
use yii\{
    base\ErrorHandler, base\Exception, web\HttpException, web\ServerErrorHttpException, db\Exception as ExceptionDb
};

/**
 * Class RestUserReputationRepository
 *
 * @package common\models\user_reputation\repositories
 */
trait RestUserReputationRepository
{

    /**
     * Method of add reputation. Using REST API
     *
     * @return array
     * @param $data
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function addUserReputation($data): array
    {
        $this->setScenario(self::SCENARIO_CREATE);
        $data['created_by'] = Yii::$app->user->identity->getId();
        $this->setAttributes($data);
        $this->validateActionValue($data['action']);

        try {
            if ($this->save()) {
                $userProfile = UserProfileEntity::findOne(['user_id' => $data['recipient_id']]);
                $userProfile->setScenario(UserProfileEntity::SCENARIO_UPDATE);

                ($data['action'] == -1)
                    ? $userProfile->setAttribute('reputation',
                    $userProfile->getAttribute('reputation') - 1)
                    : $userProfile->setAttribute('reputation',
                    $userProfile->getAttribute('reputation') + 1);

                if ($userProfile->save()) {
                    Yii::$app->getResponse()->setStatusCode(201, 'Created');
                    return [
                        'status'  => Yii::$app->response->statusCode,
                        'message' => 'Репутация пользователю успешно добавлена',
                        'data'    => $userProfile->getAttributes()
                    ];
                } elseif ($userProfile->hasErrors) {
                    $this->validationExceptionFirstMessage($userProfile->errors);
                }
            } elseif ($this->hasErrors()) {
                $this->validationExceptionFirstMessage($this->errors);
                $this->validateReputationCreator();
            }
        } catch (ExceptionDb $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при добавлении репутации.');
        }

        throw new ServerErrorHttpException('Произошла ошибка при добавлении репутации.');
    }

    /**
     * Method of get user reputation. Using REST API
     *
     * @return int
     * @param  $userId
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function getUserReputation($userId): int
    {
        $userProfile = UserProfileEntity::find()
            ->select(['user_profile.reputation'])
            ->where(['user_id' => $userId])
            ->asArray()
            ->one();

        return (int) $userProfile['reputation'];
    }
}