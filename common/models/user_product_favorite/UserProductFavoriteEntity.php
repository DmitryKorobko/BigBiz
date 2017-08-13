<?php
namespace common\models\user_product_favorite;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use \cornernote\linkall\LinkAllBehavior;
use common\models\product\ProductEntity;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UserProductFavoriteEntity
 *
 * @package common\models\user_product_favorite
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserProductFavoriteEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_product_favorite}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'id'),
            'user_id'    => Yii::t('app', 'Идентификатор пользователя'),
            'product_id' => Yii::t('app', 'Идентификатор продукта'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата последнего обновления')
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [
                ['product_id'],
                'isExistingProduct',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['product_id', 'user_id'],
                'isExistingProductFavorite',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
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
                }
            ],
            [
                'class' => LinkAllBehavior::className()
            ]
        ];
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

    /**
     * isExistingProductFavorite validator. Checking if product added in favorite.
     *
     * @throws BadRequestHttpException
     * @return bool
     */
    public function isExistingProductFavorite(): bool
    {
        $productFavorite = self::find()
            ->where(['product_id' => Yii::$app->getRequest()->getBodyParams()['product_id'],
                'user_id' =>  Yii::$app->user->identity->getId()])
            ->one();
        if (!empty($productFavorite)) {
            throw new BadRequestHttpException('Данный товар уже добавлен в ваш список избранных');
        }

        return true;
    }
}