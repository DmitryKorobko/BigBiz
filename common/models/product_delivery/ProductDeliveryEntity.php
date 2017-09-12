<?php

namespace common\models\product_delivery;

use common\models\product\ProductEntity;
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord
};
use Yii;
use common\models\product_delivery\repositories\BackendProductDeliveryRepository;

/**
 * Class ProductDeliveryEntity
 *
 * @package common\models\product_delivery
 * @property integer $id
 * @property string  $address
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductDeliveryEntity extends ActiveRecord
{
    use BackendProductDeliveryRepository;

    public $name;
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     *
     */
    public static function tableName(): string
    {
        return '{{%product_delivery}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'id',
            'address'          => 'Адрес доставки',
            'product_id'       => 'Продукт',
            'created_at'       => 'Дата создания',
            'updated_at'       => 'Дата последнего обновления'
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['address', 'product_id'], 'required'],
            [
                ['product_id'], 'exist',
                'skipOnError'     => false,
                'targetClass'     => ProductEntity::className(),
                'targetAttribute' => ['product_id' => 'id'],
            ],
            ['address', 'string'],
            ['product_id', 'integer']
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
            ],
        ];
    }
}