<?php
namespace backend\models;

use common\models\user\UserEntity;
use Yii;

/**
 * Class BackendUserEntity
 *
 * @package backend\models
 *
 * @property string $scenario
 * @property string $password_hash
 */
class BackendUserEntity extends UserEntity
{
    use BackendUserRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_VERIFY_PROFILE = 'verify';

    public $confirm;
    public $currentPassword;
    public $role;
    public $countThemes;
    public $countComments;
    public $countMessages;
    public $countShopReviews;
    public $countProductReviews;
    public $reputation;
    public $lastVisitTime;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['password_hash', 'confirm'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [
                'password_hash',
                'compare',
                'compareAttribute' => 'confirm',
            ],
            [
                'email',
                'unique',
                'targetClass' => self::className(),
                'message'     => 'Данный email уже занят.',
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            ['email', 'email', 'on' => self::SCENARIO_CREATE],
            [
                'confirm',
                'compare',
                'compareAttribute' => 'password_hash',
                'on'               => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['created_at', 'role'], 'safe'],
            [['password_hash'], 'string', 'length' => [4, 255]],
            ['status', 'default', 'value' => self::STATUS_VERIFIED],
            ['status', 'in', 'range' => [self::STATUS_VERIFIED, self::STATUS_DELETED, self::STATUS_UNVERIFIED]],
            ['verification_code', 'required', 'on' => [self::SCENARIO_VERIFY_PROFILE]]
        ];
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'               => 'Email',
            'currentPassword'     => 'Текущий пароль',
            'password_hash'       => 'Пароль',
            'confirm'             => 'Подтверждение пароля',
            'role'                => 'Роль',
            'status'              => 'Статус',
            'verification_code'   => 'Код активации',
            'refresh_token'       => 'Refresh token',
            'status_online'       => 'Статус онлайн',
            'created_at'          => 'Дата создания',
            'updated_at'          => 'Дата последнего обновления',
            'countThemes'         => 'Количество тем',
            'countComments'       => 'Количество комментариев',
            'countMessages'       => 'Количество сообщений',
            'countShopReviews'    => 'Количество отзывов о магазинах',
            'countProductReviews' => 'Количество отзывов о товарах',
            'reputation'          => 'Количество репутация',
            'lastVisitTime'       => 'Время последнего визита на сайт'

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

        if ($this->scenario == self::SCENARIO_CREATE) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        }

        if ($this->scenario == self::SCENARIO_UPDATE) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->setRole(Yii::$app->request->post()['RegistrationForm']['role']);
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

        if ($this->scenario == self::SCENARIO_UPDATE) {
            Yii::$app->authManager->revokeAll($this->getId());
            $userRole = Yii::$app->authManager->getRole($this->role);
            Yii::$app->authManager->assign($userRole, $this->getId());
        }
    }

    /**
     * @param $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        \Yii::$app->authManager->revokeAll($this->getId());
        return parent::beforeDelete();
    }
}

