<?php
namespace common\models\website_banner;

use common\{
    behaviors\ImageBehavior, models\website_banner\repositories\FrontendWebsiteBannerRepository,
    models\settings\SettingsEntity
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, data\ActiveDataProvider, db\ActiveRecord
};

/**
 * Class WebsiteBannerEntity
 *
 * @package common\models\website_banner
 *
 * @property integer $id
 * @property string $image
 * @property integer $user_id
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class WebsiteBannerEntity extends ActiveRecord
{
    use FrontendWebsiteBannerRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const IMAGE_MAX = 'website_banner_max_size';
    const IMAGE_MIN = 'website_banner_min_size';
    const PRICE     = 'website_banner_price';

    private $image_max_size;
    private $image_min_size;

    /** @var  $period_of_time */
    public $period_of_time;

    /** @var  $price */
    public $price;

    /** @var  $count_days */
    public $count_days;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%website_banner}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'image'      => 'Изображение',
            'user_id'    => 'Автор',
            'price'      => 'Стоимость (грн)',
            'count_days' => 'Количество дней',
            'start_date' => 'Дата начала отображения',
            'end_date'   => 'Дата завершения отображения',
            'status'     => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата последнего обновления'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['start_date', 'end_date'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['user_id'], 'integer'],
            [['image'], 'file', 'skipOnEmpty' => false, 'on' => [self::SCENARIO_CREATE]],
            [['image'], 'file', 'skipOnEmpty' => true, 'on' => [self::SCENARIO_UPDATE]],
            [
                'image',
                'image',
                'maxSize'     => $this->image_max_size,
                'minSize'     => $this->image_min_size,
                'minWidth'    => SettingsEntity::findOne(['key' => 'website_banner_min_width'])->value
                    ?? Yii::$app->params['website_banner_min_width'],
                'minHeight'   => SettingsEntity::findOne(['key' => 'website_banner_min_height'])->value
                    ?? Yii::$app->params['website_banner_min_height'],
                'maxWidth'    => SettingsEntity::findOne(['key' => 'website_banner_max_width'])->value
                    ?? Yii::$app->params['website_banner_max_width'],
                'maxHeight'   => SettingsEntity::findOne(['key' => 'website_banner_max_height'])->value
                    ?? Yii::$app->params['website_banner_max_height'],
                'tooBig'      => 'Файл {file} слишком большой. Размер не должен превышать '
                    . Yii::$app->formatter->asShortSize($this->image_max_size, 1),
                'tooSmall'    => 'Файл {file} слишком маленький. Размер не должен быть меньше '
                    . Yii::$app->formatter->asShortSize($this->image_min_size, 1),
                'overWidth'   => 'Файл {file} слишком большой. Ширина не должна превышать {limit} пикселей.',
                'underWidth'  => 'Файл {file} слишком маленький. Ширина не должна быть меньше {limit} пикселей.',
                'overHeight'  => 'Файл {file} слишком большой. Высота не должна превышать {limit} пикселей.',
                'underHeight' => 'Файл {file} слишком маленький. Высота не должна быть меньше {limit} пикселей.',
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['created_at', 'updated_at', 'user_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->image_max_size = !empty(SettingsEntity::findOne(['key' => 'website_banner_max_size'])->value)
            ? SettingsEntity::findOne(['key' => 'website_banner_max_size'])->value * 1024
            : Yii::$app->params['website_banner_max_size'];
        $this->image_min_size = !empty(SettingsEntity::findOne(['key' => 'website_banner_min_size'])->value)
            ? SettingsEntity::findOne(['key' => 'website_banner_min_size'])->value * 1024
            : Yii::$app->params['website_banner_min_size'];

        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return strtotime(date('Y-m-d H:i:s'));
                }
            ],
            'ImageBehavior' => [
                'class'           => ImageBehavior::className(),
                'attributeName'   => 'image',
                'savePath'        => "@webroot/images/uploads/user-{$userId}/website_banner",
                'saveWithUrl'     => true,
                'url'             => "/admin/images/uploads/user-{$userId}/website_banner/",
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->start_date = strtotime($this->start_date);
            $this->end_date = strtotime($this->end_date);
            return true;
        }

        return false;
    }

    /**
     * Method of getting list website banners. Using GridView
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $shopId = isset($params['shop_id']) ? $params['shop_id'] : Yii::$app->user->identity->getId();
        $query = self::find()->where(['user_id' => $shopId]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => [
                'pageSize' => isset($params['limit']) ? $params['limit'] : 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Turn on or off banner
     * @return bool
     */
    public function setStatus()
    {
        if ($this->status) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }

        if ($this->save()) {
            return true;
        }
        return false;
    }
}
