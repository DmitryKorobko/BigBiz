<?php

namespace rest\models;

use Yii;
use common\models\user\UserEntity;
use rest\models\traits\{
    AuthorizationJwtTrait, UserIdentityTrait
};
use yii\web\ServerErrorHttpException;

/**
 * Class RestUser
 *
 * @package rest\models
 */
class RestUser extends UserEntity
{
    use AuthorizationJwtTrait, UserIdentityTrait, RestUserRepository;

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_RECOVERY_PWD = 'recovery-password';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';
    const SECONDS_IN_DAY = 86400;

    public $name;
    public $current_password;
    public $confirm;
    public $role;
    public $terms_confirm = false;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_REGISTER] = ['email', 'password_hash', 'confirm', 'refresh_token', 'name', 'role',
            'verification_code', 'status', 'terms_confirm'];
        $scenarios[self::SCENARIO_RECOVERY_PWD] = ['email', 'password_hash', 'confirm', 'recovery_code'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'role', 'password_hash', 'confirm', 'name'], 'required', 'on' => self::SCENARIO_REGISTER],
            [
                'terms_confirm',
                'required',
                'on'            => self::SCENARIO_REGISTER,
                'requiredValue' => 1,
                'message'       => Yii::t('app', 'Вы должны принять "Пользовательские соглашения"')
            ],
            [['password_hash', 'confirm'], 'required', 'on' => [self::SCENARIO_RECOVERY_PWD, self::SCENARIO_UPDATE_PASSWORD]],
            [
                'confirm',
                'compare',
                'compareAttribute' => 'password_hash',
                'on'               => [self::SCENARIO_REGISTER, self::SCENARIO_RECOVERY_PWD]
            ],
            [['email', 'created_at', 'updated_at', 'recovery_code', 'verification_code', 'refresh_token',
                'created_recovery_code', 'status'], 'safe'],
            [['recovery_code'], 'required', 'on' => self::SCENARIO_RECOVERY_PWD],
            [['current_password'], 'required', 'on' => self::SCENARIO_UPDATE_PASSWORD],
            [['password_hash', 'email'], 'string', 'max' => 255],
            ['email', 'email', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_RECOVERY_PWD]],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_SHOP]],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Данный email уже зянят')],
            ['terms_confirm', 'boolean', 'on' => self::SCENARIO_REGISTER]
        ];
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'            => Yii::t('app', 'Email'),
            'recovery_code'    => Yii::t('app', 'Код восстановления'),
            'password_hash'    => Yii::t('app', 'Пароль'),
            'current_password' => Yii::t('app', 'Текущий Пароль'),
            'role'             => Yii::t('app', 'Роль'),
            'confirm'          => Yii::t('app', 'Пароль еще раз'),
            'name'             => Yii::t('app', 'Имя пользователя/Название магазина')
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if ($this->scenario === self::SCENARIO_REGISTER || $this->scenario === self::SCENARIO_RECOVERY_PWD) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        }

        if ($this->scenario == self::SCENARIO_UPDATE_PASSWORD) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
        }

        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->scenario == self::SCENARIO_REGISTER) {
            $userRole = Yii::$app->authManager->getRole($this->role);
            Yii::$app->authManager->assign($userRole, $this->getId());
        }
        if ($this->scenario === self::SCENARIO_RECOVERY_PWD) {
            $this->resetRecoveryCode();
        }
    }

    /**
     * User email validator. Checking if the user email not found in db.
     *
     * @param $email
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function validateUserEmail($email): bool
    {
        /** @var  $restUser RestUser.php */
        $restUser = RestUser::findOne(['email' => $email]);

        if (!$restUser) {
            throw new ServerErrorHttpException('Пользователя с таким email не существует, 
            пройдите процедуру регистрации.');
        }

        return true;
    }

    /**
     * Method of getting user role by id
     * @param $userId
     * @return string
     */
    public function getUserRole($userId): string
    {
        return current(Yii::$app->authManager->getRolesByUser($userId))->name;
    }
}
