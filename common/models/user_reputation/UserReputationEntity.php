<?php
namespace common\models\user_reputation;

use common\models\{
    user\UserEntity, user_reputation\repositories\RestUserReputationRepository, user_profile\UserProfileEntity
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord, db\Exception as ExceptionDb,
    web\ServerErrorHttpException
};
use rest\models\RestUser;

/**
 * Class UserReputationEntity
 *
 * @package common\models\user_reputation
 *
 * @property integer $id
 * @property integer $recipient_id
 * @property integer $created_by
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserReputationEntity extends ActiveRecord
{
    use RestUserReputationRepository;

    const SCENARIO_CREATE = 'create';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_reputation}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => 'Идентификатор репутации',
            'recipient_id' => 'Адресат репутации',
            'created_by'   => 'Автор репутации',
            'text'         => 'Комментарий к репутации',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата последнего обновления'
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['recipient_id', 'created_by'],
                'required',
                'on' => [self::SCENARIO_CREATE]
            ],
            [
                ['recipient_id', 'created_by'],
                'integer'
            ],
            [
                ['text'],
                'string'
            ],
            [
                ['recipient_id', 'created_by'],
                'validateReputationCreator',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE]
            ],
            [
                ['recipient_id'],
                'validateReputationRecipient',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return strtotime(date('Y-m-d H:i:s'));
                },
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'created_by']);
    }

    /**
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    private function validationExceptionFirstMessage($modelErrors): bool
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
    }

    /**
     * Reputation creator validator. Checking if the user left a reputation for this user.
     *
     * @return bool
     */
    public function validateReputationCreator(): bool
    {
        $reputation = self::find()
            ->select(['user_reputation.id', 'user_reputation.recipient_id', 'user_reputation.created_by'])
            ->where([
                'user_reputation.recipient_id' => $this->recipient_id,
                'user_reputation.created_by'   => $this->created_by
            ])
            ->asArray()
            ->one();
        if (!empty($reputation)) {
            $this->addError('created_by',
                Yii::t('app', 'Вы уже добавляли репутацию этому пользователю'));
            return false;
        }

        return true;

    }

    /**
     * Action value validator. Checking value of action.
     *
     * @param $value
     * @throws ServerErrorHttpException
     * @return bool
     */
    public function validateActionValue($value): bool
    {
        if ($value != -1 && $value != 1) {
            throw new ServerErrorHttpException('Параметр action должен быть равен 1 или -1');
        }

        return true;
    }

    /**
     * Reputation recipient validator. Checking if the recipient is shop or have guest status.
     *
     * @return bool
     */
    public function validateReputationRecipient(): bool
    {
        /**
         * @var  $restUser RestUser.php
         * @var $userProfile UserProfileEntity.php
         */
        $restUser = RestUser::findOne(['id' => Yii::$app->request->post()['recipient_id']]);
        $userProfile = UserProfileEntity::findOne(['user_id' => Yii::$app->request->post()['recipient_id']]);

        if (in_array($restUser->status, [RestUser::STATUS_GUEST, RestUser::STATUS_UNVERIFIED, RestUser::STATUS_BANNED])
            || !$userProfile) {
            $this->addError('recipient_id', Yii::t('app', 'Доступ запрещён!'));
            return false;
        }

        return true;
    }
}