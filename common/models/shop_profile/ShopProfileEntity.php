<?php
namespace common\models\shop_profile;

use Yii;
use yii\{
    db\ActiveQuery, behaviors\TimestampBehavior, db\ActiveRecord
};
use cornernote\linkall\LinkAllBehavior;
use common\{
    behaviors\ImageBehavior, models\city\CityEntity, models\profile_city\ProfileCityEntity,
    models\shop_profile\repositories\BackendShopProfileRepository, models\user\UserEntity,
    models\shop_profile\repositories\RestShopProfileRepository,
    models\shop_profile\repositories\FrontendShopProfileRepository,
    models\shop_profile\repositories\CommonShopProfileRepository
};

/**
 * Class ShopProfileEntity
 *
 * @package common\models\shop_profile
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $status_text
 * @property string $image
 * @property string $site_url
 * @property string $work_start_time
 * @property string $work_end_time
 * @property string $skype
 * @property string $viber
 * @property string $telegram
 * @property string $jabber
 * @property string $vipole
 * @property integer $category_start
 * @property integer $category_end
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShopProfileEntity extends ActiveRecord
{
    use BackendShopProfileRepository, RestShopProfileRepository, FrontendShopProfileRepository,
        CommonShopProfileRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    var $towns;

    var $_categoryEnd = null, $_categoryStart = null, $_categoryDate = null;

    private static $messengers = [
        'skype',
        'viber',
        'telegram',
        'jabber',
        'vipole',
    ];

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%shop_profile}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name'            => 'Название магазина',
            'description'     => 'Описание',
            'status_text'     => 'Ваш статус (будет отображаться под вашим лого)',
            'image'           => 'Фото магазина',
            'site_url'        => 'Сайт магазина',
            'work_start_time' => 'Время начала работы',
            'work_end_time'   => 'Время закрытия магазина',
            'skype'           => 'Skype',
            'viber'           => 'Viber',
            'telegram'        => 'Telegram',
            'jabber'          => 'Jabber',
            'vipole'          => 'Vipole',
            'towns'           => 'Города',
            '_categoryStart'  => 'Дата начала категории',
            '_categoryEnd'    => 'Дата окончания категории',
            'created_at'      => 'Дата регистрации',
            'updated_at'      => 'Дата изменения'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['name', 'work_start_time', 'work_end_time', 'towns',],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['_categoryStart', '_categoryEnd',],
                'validateDates',
                'skipOnEmpty' => false,
                'skipOnError' => false,
            ],
            [
                ['work_start_time', 'work_end_time',],
                'validateTime',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
            ],
            [
                [
                    'status_text',
                    'description',
                    'site_url',
                    'category_start',
                    'category_end',
                    '_categoryStart',
                    '_categoryEnd',
                ],
                'safe'
            ],
            [
                self::$messengers,
                'validateMessengers',
                'skipOnError' => false,
                'skipOnEmpty' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
            ],
            [
                ['skype', 'viber', 'telegram', 'jabber', 'vipole', 'site_url'],
                'validateShopContacts',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
            ],
            ['site_url', 'url', 'defaultScheme' => 'http'],
            [['image'], 'file', 'skipOnEmpty' => true, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['user_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');

        return [
            [
                'class' => LinkAllBehavior::className()
            ],
            'ImageBehavior' => [
                'class'         => ImageBehavior::className(),
                'attributeName' => 'image',
                'savePath'      => "@webroot/images/uploads/user-{$userId}/profile",
                'saveWithUrl'   => true,
                'url'           => "/admin/images/uploads/user-{$userId}/profile/"
            ],
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
     * Validate time
     *
     * @return bool
     */
    public function validateTime(): bool
    {
        $start = str_replace(':', '', $this->work_start_time);
        $end = str_replace(':', '', $this->work_end_time);
        if ($start >= $end) {
            $this->addError('work_start_time',
                Yii::t('app', 'Время работы магазина должно начинаться раньше, чем заканчиваться'));
            return false;
        }

        return true;
    }

    /**
     * Messengers validator. Need to fill 1/N messengers input to pass validation.
     *
     * @return bool
     */
    public function validateMessengers(): bool
    {
        $isValidate = false;
        foreach (self::$messengers as $messenger) {
            if ($this->$messenger) {
                $isValidate = true;
            }
        }

        foreach (self::$messengers as $messenger) {
            $this->clearErrors($messenger);
        }

        if (!$isValidate) {
            foreach (self::$messengers as $messenger) {
                $this->addError($messenger,
                    Yii::t('app', 'По крайней мере один из контактов должен быть заполнен'));
            }
        }

        return $isValidate;
    }

    /**
     * Method of validating dates
     *
     * @return bool
     */
    public function validateDates(): bool
    {
        if (Yii::$app->controller->id == 'shop') {
            $isValidate = true;

            if (!$this->_categoryStart) {
                $this->addError('_categoryDate',
                    Yii::t('app', 'Заполните дату!'));
                $isValidate = false;
            }

            if (!$this->_categoryEnd) {
                $this->addError('_categoryDate',
                    Yii::t('app', 'Заполните дату!'));
                $isValidate = false;
            }

            return $isValidate;
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities(): ActiveQuery
    {
        return $this->hasMany(CityEntity::className(), ['id' => 'city_id'])
            ->viaTable(ProfileCityEntity::tableName(), ['profile_id' => 'id']);
    }

    /**
     * Shop contacts validator. Finding concurrences shop contacts in db.
     *
     * @return bool
     */
    public function validateShopContacts(): bool
    {
        $shopId = Yii::$app->user->getId();
        $siteUrl = ShopProfileEntity::findOne([
            'site_url' => Yii::$app->request->post()['ShopProfileEntity']['site_url']
        ]);
        $skype = ShopProfileEntity::findOne([
            'skype' => Yii::$app->request->post()['ShopProfileEntity']['skype']
        ]);
        $viber = ShopProfileEntity::findOne([
            'viber' => Yii::$app->request->post()['ShopProfileEntity']['viber']
        ]);
        $vipole = ShopProfileEntity::findOne([
            'vipole' => Yii::$app->request->post()['ShopProfileEntity']['vipole']
        ]);
        $jabber = ShopProfileEntity::findOne([
            'jabber' => Yii::$app->request->post()['ShopProfileEntity']['jabber']
        ]);
        $telegram = ShopProfileEntity::findOne([
            'telegram' => Yii::$app->request->post()['ShopProfileEntity']['telegram']
        ]);

        if (Yii::$app->request->post()['ShopProfileEntity']['site_url'] != "") {
            if (!empty($siteUrl) && $siteUrl->user_id != $shopId) {
                $this->addError('site_url', Yii::t('app', 'Такие данные уже есть в базе!'));
                return false;
            }
        }
        if (Yii::$app->request->post()['ShopProfileEntity']['skype'] != "") {
            if (!empty($skype) && $skype->user_id != $shopId) {
                $this->addError('skype', Yii::t('app', 'Такие данные уже есть в базе!'));
                return false;
            }
        }
        if (Yii::$app->request->post()['ShopProfileEntity']['viber'] != "") {
            if (!empty($viber) && $viber->user_id != $shopId) {
                $this->addError('viber', Yii::t('app', 'Такие данные уже есть в базе!'));
                return false;
            }
        }
        if (Yii::$app->request->post()['ShopProfileEntity']['vipole'] != "") {
            if (!empty($vipole) && $vipole->user_id != $shopId) {
                $this->addError('vipole', Yii::t('app', 'Такие данные уже есть в базе!'));
                return false;
            }
        }
        if (Yii::$app->request->post()['ShopProfileEntity']['jabber'] != "") {
            if (!empty($jabber) && $jabber->user_id != $shopId) {
                $this->addError('jabber', Yii::t('app', 'Такие данные уже есть в базе!'));
                return false;
            }
        }
        if (Yii::$app->request->post()['ShopProfileEntity']['telegram'] != "") {
            if (!empty($telegram) && $telegram->user_id != $shopId) {
                $this->addError('telegram', Yii::t('app', 'Такие данные уже есть в базе!'));
                return false;
            }
        }

        return true;
    }
}

