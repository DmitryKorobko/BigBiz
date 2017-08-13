<?php
namespace backend\modules\authorization\models;

use backend\models\BackendUserEntity;
use yii\{
    base\Model, web\HttpException, base\Exception
};
use common\models\{
    user\UserEntity, shop_notifications_settings\ShopNotificationsSettingsEntity,
    shop_confidentiality\ShopConfidentialityEntity, user_confidentiality\UserConfidentialityEntity,
    user_notifications_settings\UserNotificationsSettingsEntity, shop_profile\ShopProfileEntity,
    user_profile\UserProfileEntity
};
use Yii;

/**
 * Registration form
 */
class RegistrationForm extends Model
{
    public $email;
    public $password;
    public $passwordRepeat;
    public $verificationCode;
    public $termsConditions;
    public $name;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'passwordRepeat', 'name', 'role'], 'required'],
            ['email', 'email', 'message' => 'Неверный формат E-mail'],
            ['email', 'validateEmail'],
            ['password', 'string', 'min' => 5],
            [
                'passwordRepeat',
                'compare',
                'compareAttribute' => 'password',
                'message'          => 'Пароли не совпадают'
            ],
            [
                'termsConditions',
                'required',
                'requiredValue' => 1,
                'message'       => Yii::t('app', 'Вы должны принять "Пользовательские соглашения" для
                дальнейшей регистрации')
            ],
            [
                'role',
                'in',
                'range'         => ['user', 'shop'],
                'message'       => Yii::t('app', 'Необходимо выбрать роль - Пользователь или Магазин')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role'            => 'Пользователь/Магазин',
            'name'            => 'Имя пользователя/Название магазина',
            'email'           => 'E-mail',
            'password'        => 'Пароль',
            'passwordRepeat'  => 'Подтверждение пароля',
            'termsConditions' => 'Я согласен с условиями и правилами форума'
        ];
    }

    /**
     * Main registration method
     * return true if everything is ok and false if not
     *
     * @return bool|BackendUserEntity
     */
    public function registration()
    {
        if ($this->validate() && $this->registerUser()) {
            /** @var  $user BackendUserEntity.php */
            $user = BackendUserEntity::findByEmail($this->email);
            $this->verificationCode = $user->verification_code;

            return $user;
        }

        return false;
    }

    /**
     * registerUser method sets all needed properties to UserEntity model,
     * save it and in success case assign shop/user role
     *
     * @throws HttpException
     */
    public function registerUser()
    {
        $user = new UserEntity();
        $user->email = $this->email;
        $user->refresh_token = base64_encode(md5(time()) . md5(rand(1000, 9999)));
        $user->setPassword($this->password);
        $user->scenario = UserEntity::SCENARIO_CREATE;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($user->save()) {
                if ($this->role === UserEntity::ROLE_SHOP) {
                    /**
                     * @var  $confidentiality ShopConfidentialityEntity.php
                     * @var  $notificationsSettings ShopNotificationsSettingsEntity.php
                     * @var  $profile ShopProfileEntity.php
                     */
                    $profile = ShopProfileEntity::findOne(['user_id' => $user->id]);
                    $confidentiality = ShopConfidentialityEntity::findOne(['user_id' => $user->id]);
                    $notificationsSettings = ShopNotificationsSettingsEntity::findOne(['user_id' => $user->id]);

                    if (!$profile) {
                        $profile = new ShopProfileEntity();
                        $profile->createShopProfile($user->id, $this->name);
                    }

                    if (!$confidentiality) {
                        $confidentiality = new ShopConfidentialityEntity();
                        $confidentiality->createShopProfileConfidentiality($user->id);
                    }

                    if (!$notificationsSettings) {
                        $notificationsSettings = new ShopNotificationsSettingsEntity();
                        $notificationsSettings->createShopProfileNotificationsSettings($user->id);
                        $transaction->commit();
                    }
                } else {
                    /**
                     * @var  $confidentiality UserConfidentialityEntity.php
                     * @var  $notificationsSettings UserNotificationsSettingsEntity.php
                     * @var  $profile UserProfileEntity.php
                     */
                    $profile = UserProfileEntity::findOne(['user_id' => $user->id]);
                    $confidentiality = UserConfidentialityEntity::findOne(['user_id' => $user->id]);
                    $notificationsSettings = UserNotificationsSettingsEntity::findOne(['user_id' => $user->id]);

                    if (!$profile) {
                        $profile = new UserProfileEntity();
                        $profile->createUserProfile($user->id, $this->name, 1);
                    }

                    if (!$confidentiality) {
                        $confidentiality = new UserConfidentialityEntity();
                        $confidentiality->createUserProfileConfidentiality($user->id);
                    }

                    if (!$notificationsSettings) {
                        $notificationsSettings = new UserNotificationsSettingsEntity();
                        $notificationsSettings->createUserProfileNotificationsSettings($user->id);
                        $transaction->commit();
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('error', 'Произошла ошибка при регистрации!');
            return false;
        }

        return true;
    }

    /**
     * Check email on the unique value
     *
     * @param $attribute
     */
    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            $email = $this->getEmail();
            if ($email) {
                $this->addError($attribute, 'Данный E-mail уже зарегистрирован');
            }
        }
    }

    /**
     * Getting email for validate
     *
     * @return UserEntity
     */
    public function getEmail()
    {
        return UserEntity::findByEmail($this->email);
    }
}
