<?php
namespace common\models\answer\repositories;

use common\models\{
    answer\AnswerEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity,
    user\repositories\UserRepository, admin_contact\AdminContactEntity
};
use Yii;
use yii\{
    base\ErrorHandler, base\Exception, web\HttpException, web\ServerErrorHttpException, db\Exception as ExceptionDb,
    data\ArrayDataProvider
};
/**
 * Class RestAnswerRepository
 *
 * @package common\models\answer\repositories
 */
trait RestAnswerRepository
{
    /**
     * Method of add answer. Using REST API
     *
     * @return array
     * @param $data
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function addAnswer($data): array
    {
        $this->setScenario(self::SCENARIO_CREATE);
        $this->setAttributes($data);

        try {
            $this->validate();
            if ($this->save()) {
                Yii::$app->getResponse()->setStatusCode(201);
                return ['answer_id' => $this->id];
            }
        } catch (ExceptionDb $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при добавлении ответа.');
        }

        throw new ServerErrorHttpException('Произошла ошибка при добавлении ответа.');

    }

    /**
     * Method of getting list of answers for shop or user.
     *
     * @param $userId
     * @param $status
     * @return ArrayDataProvider
     */
    public function getListAnswers($userId, $status): ArrayDataProvider
    {
        $answers = [];

        $models = AnswerEntity::find()
            ->select([
                'answer.id', 'answer.type', 'answer.recipient_id', 'answer.product_id',
                'user_profile.nickname as user_name', 'shop_profile.name as shop_name',
                'answer.theme_id', 'answer.comment_id', 'answer.created_by',
                'answer.text', 'answer.status', 'answer.created_at'
            ])
            ->leftJoin('user_profile', 'user_profile.user_id = answer.created_by')
            ->leftJoin('shop_profile', 'shop_profile.user_id = answer.created_by')
            ->where(['answer.recipient_id' => $userId, 'answer.status' => $status])
            ->orderBy(['answer.created_at' => 'desc'])
            ->asArray()
            ->all();

        if ($models) {
            foreach ($models as $answer) {
                $answerWithoutUserName = [
                    'id'         => (int) $answer['id'],
                    'type'       => $answer['type'],
                    'created_at' => (int) $answer['created_at'],
                    'text'       => $answer['text'],
                    'status'     => $answer['status']
                ];
                ($answer['product_id']) ? $answerWithoutUserName['product_id'] = (int) $answer['product_id'] : false;
                ($answer['theme_id'])   ? $answerWithoutUserName['theme_id']   = (int) $answer['theme_id']   : false;
                ($answer['comment_id']) ? $answerWithoutUserName['comment_id'] = (int) $answer['comment_id'] : false;
                /**
                 * @var  $shop ShopProfileEntity
                 * @var  $user UserProfileEntity
                 */
                $shop = ShopProfileEntity::findOne(['user_id' => $answer['created_by']]);
                $user = UserProfileEntity::findOne(['user_id' => $answer['created_by']]);

                if ($shop) {
                    $answerWithoutUserName['created_by'] = [
                        'user_name' => $shop->name,
                        'avatar'    => $shop->image,
                        'is_online' => UserRepository::isOnline($answer['created_by'])
                    ];
                } elseif($user) {
                    $answerWithoutUserName['created_by'] = [
                        'user_name' => $user->nickname,
                        'avatar'    => $user->avatar,
                        'is_online' => UserRepository::isOnline($answer['created_by'])
                    ];
                } else {
                    $answerWithoutUserName['created_by'] = [
                        'name'          => 'Администрация',
                        'avatar'        => (new AdminContactEntity())->getCurrentImage($answer['created_by']),
                        'status_online' => UserRepository::isOnline($answer['created_by'])
                    ];
                }

                $answers[] = $answerWithoutUserName;
            }
        }

        if ($status === AnswerEntity::STATUS_UNREAD) {
            $answerModel = new AnswerEntity();
            $answerModel->setScenario(AnswerEntity::SCENARIO_UPDATE);
            $answerModel::updateAll(['status' => AnswerEntity::STATUS_READ], 'recipient_id = ' . $userId );
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $answers,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $dataProvider;
    }
}
