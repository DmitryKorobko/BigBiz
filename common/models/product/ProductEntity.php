<?php
namespace common\models\product;

use common\{
    behaviors\ImageBehavior, models\city\CityEntity, models\product\repositories\BackendProductRepository,
    models\product\repositories\RestProductRepository, models\product_city\ProductCityEntity,
    models\product_price\ProductPriceEntity, models\product\repositories\FrontendProductRepository,
    models\product\repositories\CommonProductRepository, models\settings\SettingsEntity
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord
};
use \cornernote\linkall\LinkAllBehavior;

/**
 * Class ProductEntity
 *
 * @package common\models
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $availability
 * @property string $image
 * @property integer $user_id
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductEntity extends ActiveRecord
{
    use BackendProductRepository, RestProductRepository, FrontendProductRepository, CommonProductRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const IMAGE_MAX = 'product_image_max_size';
    const IMAGE_MIN = 'product_image_min_size';

    public $towns;
    public $count_new_report;
    public $count_report;
    public $product_created_range;

    private $image_max_size;
    private $image_min_size;

    /**
     * Table name - Product.
     * @inheritdoc
     *
     */
    public static function tableName(): string
    {
        return '{{%product}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'id',
            'name'             => 'Название',
            'description'      => 'Описание',
            'availability'     => 'Доступен',
            'image'            => 'Изображения',
            'user_id'          => 'Автор',
            'sort'             => 'Сортировка',
            'towns'            => 'Города',
            'cities'           => 'Города',
            'prices'           => 'Цены',
            'count_report'     => 'Общее кол-во репортов',
            'count_new_report' => 'Кол-во новых репортов',
            'created_at'       => 'Дата создания',
            'updated_at'       => 'Дата последнего обновления'
        ];
    }

    public function rules()
    {
        return [
            [
                ['name', 'description', 'towns'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['availability', 'sort'], 'integer'],
            [['sort'], 'default', 'value' => 1],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
            [['image'], 'file', 'skipOnEmpty' => true,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [
                ['image'],
                'image',
                'maxSize'     => $this->image_max_size,
                'minSize'     => $this->image_min_size,
                'minWidth'    => SettingsEntity::findOne(['key' => 'product_image_min_width'])->value
                    ?? Yii::$app->params['product_image_min_width'],
                'minHeight'   => SettingsEntity::findOne(['key' => 'product_image_min_height'])->value
                    ?? Yii::$app->params['product_image_min_height'],
                'maxWidth'    => SettingsEntity::findOne(['key' => 'product_image_max_width'])->value
                    ?? Yii::$app->params['product_image_max_width'],
                'maxHeight'   => SettingsEntity::findOne(['key' => 'product_image_max_height'])->value
                    ?? Yii::$app->params['product_image_max_height'],
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
            [['availability', 'name', 'description', 'created_at', 'updated_at', 'sort', 'product_created_range'], 'safe'],
            ['description', 'string', 'min' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->image_max_size = !empty(SettingsEntity::findOne(['key' => 'product_image_max_size'])->value)
            ? SettingsEntity::findOne(['key' => 'product_image_max_size'])->value * 1024
            : Yii::$app->params['product_image_max_size'];
        $this->image_min_size = !empty(SettingsEntity::findOne(['key' => 'product_image_min_size'])->value)
            ? SettingsEntity::findOne(['key' => 'product_image_min_size'])->value * 1024
            : Yii::$app->params['product_image_min_size'];

        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return strtotime(date('Y-m-d H:i:s'));
                },
            ],
            [
                'class' => LinkAllBehavior::className()
            ],
            'ImageBehavior' => [
                'class'         => ImageBehavior::className(),
                'attributeName' => 'image',
                'savePath'      => "@webroot/images/uploads/user-{$userId}/product",
                'saveWithUrl'   => true,
                'url'           => "/admin/images/uploads/user-{$userId}/product/",
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices(): ActiveQuery
    {
        return $this->hasMany(ProductPriceEntity::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities(): ActiveQuery
    {
        return $this->hasMany(CityEntity::className(), ['id' => 'city_id'])->viaTable(ProductCityEntity::tableName(),
            ['product_id' => 'id']);
    }

    /**
     * Get current value of availability column
     * @return int
     */
    public function getAvailability(): int
    {
        return $this->availability;
    }

    /**
     * Sets availability value
     * @return bool
     */
    public function setAvailability(): bool
    {
        if ($this->getAvailability()) {
            $this->availability = 0;
        } else {
            $this->availability = 1;
        }

        if ($this->save()) {
            return true;
        }
        return false;
    }
}