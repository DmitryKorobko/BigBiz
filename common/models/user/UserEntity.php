<?php
namespace common\models\user;

use common\models\user\repositories\{
    UserRepository, IdentityRepository
};
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord, web\IdentityInterface
};
use Yii;

/**
 * Class UserEntity
 *
 * @property integer $id
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email
 * @property integer $status_online
 * @property string  $auth_key
 * @property integer $recovery_code
 * @property integer $verification_code
 * @property integer $created_recovery_code
 * @property integer $status
 * @property integer $is_deleted
 * @property string  $refresh_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password write-only password
 *
 * @package common\models\user
 */
class UserEntity extends ActiveRecord implements IdentityInterface
{
    use IdentityRepository;
    use UserRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const ROLE_ADMIN = 'admin';
    const ROLE_SHOP  = 'shop';
    const ROLE_USER  = 'user';
    const ROLE_MODER = 'moder';
    const ROLE_GUEST = 'guest';

    const STATUS_DELETED    = 'DELETED';
    const STATUS_UNVERIFIED = 'UNVERIFIED';
    const STATUS_VERIFIED   = 'VERIFIED';
    const STATUS_GUEST      = 'GUEST';
    const STATUS_BANNED     = 'BANNED';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%user}}';
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password_hash'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [
                'status',
                'in',
                'range' => [self::STATUS_VERIFIED, self::STATUS_DELETED, self::STATUS_UNVERIFIED,
                    self::STATUS_BANNED, self::STATUS_GUEST]
            ],
            [['status'], 'safe'],
        ];
    }

    /**
     * beforeSave create scenario generate and set UserEntity model properties
     * which user don't need to input
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->scenario == self::SCENARIO_CREATE) {
                $this->generatePasswordResetToken();
                $this->generateAuthKey();
                $this->generateVerificationCode();
                $this->status = UserEntity::STATUS_UNVERIFIED;
            }

            return true;
        }

        return false;
    }

    /**
     * After saving user we assign shop or user role to him
     *
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->scenario == self::SCENARIO_CREATE) {
            $role = Yii::$app->authManager->getRole(Yii::$app->request->post()['RegistrationForm']['role']);
            Yii::$app->authManager->assign($role, $this->getId());
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
