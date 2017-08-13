<?php
namespace common\models\product_price;

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
            [['price'], 'double'],
            [['product_id', 'count', 'price', 'price_usd'], 'safe'],
            [
                ['price', 'price_usd'],
                'validatePrice',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
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

    /**
     * Price validator. Check for empty prices.
     *
     * @return bool
     */
    public function validatePrice(): bool
    {
        if ((Yii::$app->request->post()['ProductPriceEntity'][0]['price'] == '')
            && (Yii::$app->request->post()['ProductPriceEntity'][0]['price_usd'] == '')) {
            $this->addError('price',
                Yii::t('app', 'По крайней мере одна цена должна быть заполнена!'));
            $this->addError('price_usd',
                Yii::t('app', 'По крайней мере одна цена должна быть заполнена!'));
            return false;
        }

        return true;
    }
}