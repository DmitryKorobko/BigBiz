<?php
namespace backend\modules\authorization\models;

use Yii;
use yii\base\Model;
use common\models\user\UserEntity;
use yii\web\ForbiddenHttpException;

/**
 * Class LoginForm
 *
 * @package backend\modules\authorization\models
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'      => 'E-mail',
            'password'   => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     * Method of checking user status after basic validations, user role
     * and render error about deleted/inactive account
     *
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if (parent::validate($attributeNames, $clearErrors)) {
            $user = UserEntity::findByEmail($this->email);
            if ($user->status) {
                if ($user->status === UserEntity::STATUS_DELETED) {
                    $this->addError('email', 'Ваш аккаунт был удален, обратитесь к администратору');
                    return false;
                }
                if ($user->status === UserEntity::STATUS_BANNED) {
                    $this->addError('email', 'Ваш аккаунт был забанен, обратитесь к администратору');
                    return false;
                }
            }
            $role = Yii::$app->authManager->getRolesByUser($user->id);
            if (!isset($role['admin']) && !isset($role['shop']) && !isset($role['moder'])) {
                throw new ForbiddenHttpException('У вас нет доступа к данной странице');
            }
            return true;
        }

        return false;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный E-mail или пароль');
            }


        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return UserEntity|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserEntity::findByEmail($this->email);
        }

        return $this->_user;
    }
}
