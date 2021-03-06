<?php
namespace common\models\product_price;

use common\models\product\ProductEntity;
use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * Class ProductPriceEntity
 *
 * @package common\models\product_price
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $count
 * @property double $price
 * @property double $price_usd
 */
class ProductPriceEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_price}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'id'),
            'product_id' => Yii::t('app', 'product_id'),
            'count'      => Yii::t('app', 'Колличество'),
            'price'      => Yii::t('app', 'Цена (грн)'),
            'price_usd'  => Yii::t('app', 'Цена (usd)'),
        ];
    }

    public function rules()
    {
        return [
            [['price', 'price_usd'], 'double'],
            [['count', 'product_id'], 'integer'],
            [
                ['product_id'], 'exist',
                'skipOnError'     => false,
                'targetClass'     => ProductEntity::className(),
                'targetAttribute' => ['product_id' => 'id'],
            ],
            [
                'price', 'required', 'when' => function($model) {
                    return $model->price_usd == '';
                },
                'whenClient' => 'function (attribute, value) {
                    return $("#productpriceentity-0-price_usd").val() == ""; }',
                'message' => 'По крайней мере одна цена должна быть заполнена!'
            ],
            [
                'price_usd', 'required', 'when' => function($model) {
                    return $model->price == '';
                },
                'whenClient' => 'function (attribute, value) {
                    return $("#productpriceentity-0-price").val() == ""; }',
                'message' => 'По крайней мере одна цена должна быть заполнена!'
            ],
        ];
    }

    /**
     * Used for multiply save in Item-Price action's
     *
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($multipleModels = [])
    {
        $model = new self;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new self;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }
}