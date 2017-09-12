<?php

namespace common\models\product_like;

use yii\{
    db\ActiveRecord, behaviors\TimestampBehavior, web\NotFoundHttpException
};
use common\models\product\ProductEntity;
use Yii;

/**
 * Class ProductLikeEntity
 *
 * @package common\models\product_like
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property integer $like
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductLikeEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_like}}';
    }

    public function rules()
    {
        return [
            [['like', 'product_id', 'user_id'], 'required', 'on' => [ self::SCENARIO_CREATE ]],
            [['product_id', 'like', 'user_id'], 'integer'],
            [['product_id'],
                'isExistingProduct',
                'skipOnError' => false,
                'on'          => self::SCENARIO_CREATE
            ]
        ];
    }

    /**
     * @return array
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

    /**
     * Method of liking product by user
     *
     * @param $productId
     * @param $like
     * @return bool
     */
    public function likeProductByUser($productId, $like)
    {
        $model = self::findOne(['user_id' => Yii::$app->user->identity->getId(), 'product_id' => $productId]);
        if ($model) {
            $model->like = $like;
            return $model->save(false);
        }
        $model = new self();
        $model->scenario = self::SCENARIO_CREATE;
        $model->product_id = $productId;
        $model->user_id = Yii::$app->user->identity->getId();
        $model->like = $like;

        return $model->save();
    }

    /**
     * Method of getting count likes of product
     *
     * @param $productId
     * @return int|string
     */
    public function getCountProductLike($productId)
    {
        return ProductLikeEntity::find()->where(['product_id' => $productId, 'like' => 1])->count();
    }

    /**
     * isExistingProduct validator. Checking if product not exist.
     *
     * @throws NotFoundHttpException
     * @return bool
     */
    public function isExistingProduct(): bool
    {
        $product = ProductEntity::findOne(['id' => Yii::$app->getRequest()->getBodyParams()['product_id']]);
        if (!$product) {
            throw new NotFoundHttpException('Товар не найден');
        }

        return true;
    }
}