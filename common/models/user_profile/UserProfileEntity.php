<?php
namespace common\models\user_profile;

use common\models\user\UserEntity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class UserProfileEntity
 * @package common\models\user_profile
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $nickname
 * @property string $avatar
 * @property string $gender
 * @property string $status_message
 * @property integer $dob_day
 * @property integer $dob_year
 * @property integer $dob_month
 * @property integer $terms_confirm
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserProfileEntity extends ActiveRecord
{
    use UserProfileRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const GENDER_MALE   = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    public $verification_code;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname', 'user_id', 'terms_confirm'], 'required', 'on' => [ self::SCENARIO_CREATE ]],
            ['verification_code', 'required', 'on' => [self::SCENARIO_CREATE]],
            [['verification_code', 'user_id',  'terms_confirm',
                'dob_day', 'dob_month', 'dob_year'], 'number', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['nickname', 'gender'], 'string', 'length' => [2, 255], 'on' => [self::SCENARIO_UPDATE]],
            ['status_message', 'string', 'length' => [2, 50], 'on' => [self::SCENARIO_UPDATE]],
            [['created_at', 'updated_at', 'avatar'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return time();
                },
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'user_id'])->select(['email', 'status']);
    }
}

