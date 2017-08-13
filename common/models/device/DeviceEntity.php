<?php
namespace common\models\device;

use common\models\user\UserEntity;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord, db\Exception as ExceptionDb
};
use Yii;

/**
 * Class DeviceEntity
 *
 * @package common\models\device
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $device_type
 * @property string $make
 * @property string $os
 * @property string $mode
 * @property string $version
 * @property string $uuid
 * @property string $device_token
 * @property integer $created_at
 * @property integer $updated_at
 */
class DeviceEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const OS_ANDROID = 'ANDROID';
    const OS_IOS = 'iOS';

    const MODE_ALLOW = 'ALLOW';
    const MODE_DISABLED = 'DISABLED';
    const MODE_BLOCKED = 'BLOCKED';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%device}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => 'ID',
            'user_id'      => 'Идентификатор пользователя',
            'device_type'  => 'Тип девайса',
            'make'         => 'Производитель',
            'os'           => 'Операционная система',
            'version'      => 'Версия операционной системы',
            'mode'         => 'Режим для пуш нотификаций',
            'uuid'         => 'uuid',
            'device_token' => 'Девайс токен пуш нотификаций',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата изменения'
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
                    return time();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['user_id', 'os', 'uuid'], // device_token
                'required',
                'on' => [self::SCENARIO_CREATE]
            ],
            [
                ['uuid'],
                'required',
                'on' => [self::SCENARIO_UPDATE]
            ],
            [['device_type', 'make', 'version', 'uuid', 'device_token'], 'string'],
            ['mode', 'in', 'range' => [self::MODE_ALLOW, self::MODE_BLOCKED, self::MODE_DISABLED], 'strict' => true],
            ['os', 'in', 'range' => [self::OS_ANDROID, self::OS_IOS], 'strict' => true],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
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
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    private function validationExceptionFirstMessage($modelErrors) : bool
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
    }

    /**
     * Method of register user device
     *
     * @param array $postData
     * @return bool
     */
    public function createDevice(array $postData): bool
    {
        $this->scenario = DeviceEntity::SCENARIO_CREATE;

        $postData['user_id'] = Yii::$app->user->identity->getId();
        $postData['device_token'] = Yii::$app->security->generateRandomString();
        $this->load($postData, '');

        return $this->save();
    }

    /**
     * Method of updating device information
     *
     * @param array $postData
     * @return DeviceEntity | bool
     */
    public function updateDevice(array $postData)
    {
        $this->scenario = DeviceEntity::SCENARIO_UPDATE;
        $this->load($postData, '');
        if (!$this->save()) {
            return $this->validationExceptionFirstMessage($this->errors);
        }
        return $this;
    }
}
