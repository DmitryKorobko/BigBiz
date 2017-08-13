<?php
namespace common\models\shop_notifications_settings;

use backend\models\BackendUserEntity;
use common\models\{
    user\UserEntity, shop_notifications_settings\repositories\BackendShopNotificationsSettingsRepository
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord
};


/**
 * Class ShopNotificationsSettingsEntity
 *
 * @package common\models\shop_notifications_settings
 *
 * @property integer $id
 * @property integer $user_id
 * @property boolean $new_personal_message
 * @property boolean $new_review
 * @property boolean $new_reply_comment
 * @property boolean $new_product_report
 * @property boolean $new_theme_comment
 * @property boolean $theme_was_verified
 * @property boolean $new_like
 * @property boolean $messages_to_email
 * @property boolean $site_dispatch
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShopNotificationsSettingsEntity extends ActiveRecord
{
    use BackendShopNotificationsSettingsRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%shop_notifications_settings}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'                   => 'Идентификатор конфиденциальности',
            'user_id'              => 'Идентификатор пользователя',
            'new_personal_message' => 'Личное сообщение',
            'new_review'           => 'Добавление отзыва о магазине',
            'new_reply_comment'    => 'Ответ на комментарий',
            'new_product_report'   => 'Отзыв об отслеживаемом продукте',
            'new_theme_comment'    => 'Комментарий к отслеживаемой теме',
            'theme_was_verified'   => 'Тема проверена',
            'new_like'             => 'Мне нравится',
            'messages_to_email'    => 'Письмо на почту о новых сообщениях',
            'site_dispatch'        => 'Получать рассылку сайта',
            'created_at'           => 'Дата создания',
            'updated_at'           => 'Дата последнего обновления'
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
                [
                    'new_personal_message',
                    'new_review',
                    'new_reply_comment',
                    'new_product_report',
                    'new_theme_comment',
                    'theme_was_verified',
                    'new_like',
                    'messages_to_email',
                    'site_dispatch'
                ],
                'boolean',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
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
     * User validator. Checking access to shop notifications settings for this user.
     *
     * @return bool
     */
    public function validateUser(): bool
    {
        $userModel = BackendUserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);
        $assignment = Yii::$app->authManager->getAssignment('shop', $userModel->getId());

        if (empty($userModel)) {
            $this->addError('user_id', Yii::t('app', 'Пользователь не найден.'));
            return false;
        } else {
            if ($assignment && $assignment->roleName === 'shop') {
                return true;
            }
        }

        return false;
    }
}