<?php
namespace common\models\user_confidentiality;

use common\models\{
    user\UserEntity, user_confidentiality\repositories\RestUserConfidentialityRepository
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord
};
use rest\models\RestUser;

/**
 * Class UserConfidentialityEntity
 *
 * @package common\models\user_confidentiality
 *
 * @property integer $id
 * @property integer $user_id
 * @property boolean $show_date_of_birth
 * @property boolean $show_status_online
 * @property string $view_page_access
 * @enum ['ALL_USERS', 'NOBODY', 'REGISTERED_USERS'] $view_page_access
 * @property string $send_messages_access
 * @enum ['ALL_USERS', 'NOBODY', 'REGISTERED_USERS'] $send_messages_access
 * @property string $frequency_history_cleaning
 * @enum ['ONE_MINUTE', 'FIVE_MINUTES', 'ONE_HOUR', 'THREE_HOURS', 'FIVE_HOURS', 'TWELVE_HOURS',
 * 'ONE_DAY', 'SEVEN_DAYS', 'NEVER'] $frequency_history_cleaning
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserConfidentialityEntity extends ActiveRecord
{
    use RestUserConfidentialityRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const ACCESS_ALL_USERS        = 'ALL_USERS';
    const ACCESS_NOBODY           = 'NOBODY';
    const ACCESS_REGISTERED_USERS = 'REGISTERED_USERS';

    const FREQUENCY_ONE_MINUTE   = 'ONE_MINUTE';
    const FREQUENCY_FIVE_MINUTES = 'FIVE_MINUTES';
    const FREQUENCY_ONE_HOUR     = 'ONE_HOUR';
    const FREQUENCY_THREE_HOURS  = 'THREE_HOURS';
    const FREQUENCY_FIVE_HOURS   = 'FIVE_HOURS';
    const FREQUENCY_TWELVE_HOURS = 'TWELVE_HOURS';
    const FREQUENCY_ONE_DAY      = 'ONE_DAY';
    const FREQUENCY_SEVEN_DAYS   = 'SEVEN_DAYS';
    const FREQUENCY_NEVER        = 'NEVER';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_confidentiality}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'                         => 'Идентификатор конфиденциальности',
            'user_id'                    => 'Идентификатор пользователя',
            'show_date_of_birth'         => 'Отображение даты рождения',
            'show_status_online'         => 'Отображение статуса online',
            'view_page_access'           => 'Доступ к просмотру страницы',
            'send_messages_access'       => 'Доступ к отправке личных сообщений',
            'frequency_history_cleaning' => 'Частота очистки истории личных сообщений',
            'created_at'                 => 'Дата создания',
            'updated_at'                 => 'Дата последнего обновления'
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['user_id'],
                'required',
                'on' => [self::SCENARIO_CREATE]
            ],
            [
                ['user_id'],
                'integer',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['show_date_of_birth', 'show_status_online'],
                'boolean',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['view_page_access', 'send_messages_access'],
                'in',
                'range'       => self::validateEnumAccessValues(),
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['frequency_history_cleaning'],
                'in',
                'range'       => self::validateEnumFrequencyValues(),
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['user_id'],
                'validateUser',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_UPDATE]
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'user_id']);
    }

    /**
     * User validator. Checking access to confidentiality for this user.
     *
     * @return bool
     */
    public function validateUser(): bool
    {
        $userModel = RestUser::findOne(['id' => Yii::$app->user->identity->getId()]);
        $assignment = Yii::$app->authManager->getAssignment('user', $userModel->getId());

        if (!empty($userNotificationsSettings)) {
            $this->addError('user_id',
                Yii::t('app', 'Пользователь не найден.'));
            return false;
        } else {
            if ($assignment && $assignment->roleName === 'user') {
                return true;
            }
        }

        return false;
    }

    /**
     * Access ENUM validator. Checking value of access is in range ENUM values.
     *
     * @return array
     */
    protected static function validateEnumAccessValues()
    {
        return [
            self::ACCESS_ALL_USERS,
            self::ACCESS_REGISTERED_USERS,
            self::ACCESS_NOBODY
        ];
    }

    /**
     * Frequency ENUM validator. Checking value of frequency is in range ENUM values.
     *
     * @return array
     */
    protected static function validateEnumFrequencyValues()
    {
        return [
            self::FREQUENCY_ONE_MINUTE,
            self::FREQUENCY_FIVE_MINUTES,
            self::FREQUENCY_ONE_HOUR,
            self::FREQUENCY_THREE_HOURS,
            self::FREQUENCY_FIVE_HOURS,
            self::FREQUENCY_TWELVE_HOURS,
            self::FREQUENCY_ONE_DAY,
            self::FREQUENCY_SEVEN_DAYS,
            self::FREQUENCY_NEVER
        ];
    }
}