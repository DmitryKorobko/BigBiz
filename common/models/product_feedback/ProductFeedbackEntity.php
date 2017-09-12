<?php
namespace common\models\product_feedback;

use common\models\{
    product\ProductEntity, product_feedback\repositories\RestProductFeedbackRepository, user\UserEntity,
    product_feedback\repositories\BackendProductFeedbackRepository, answer\AnswerEntity,
    product_feedback\repositories\CommonProductFeedbackRepository
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord, web\NotFoundHttpException
};
/**
 * Class ProductFeedbackEntity
 *
 * @package common\models\product_feedback
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property string  $text
 * @property integer $rating
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductFeedbackEntity extends ActiveRecord
{
    use RestProductFeedbackRepository, BackendProductFeedbackRepository, CommonProductFeedbackRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $towns;

    /**
     * Table name - Product.
     * @inheritdoc
     *
     */
    public static function tableName()
    {
        return '{{%product_feedback}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'id',
            'product_id'   => 'Продукт',
            'user_id'      => 'Автор отзыва',
            'text'         => 'Сообщение',
            'rating'       => 'Общая оценка',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата последнего обновления'
        ];
    }

    public function rules()
    {
        return [
            [
                ['text', 'user_id', 'product_id', 'rating'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['user_id', 'product_id', 'rating'], 'integer'],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
            [['text'], 'string'],
            [
                ['product_id'],
                'isExistingProduct',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ]
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
                    return strtotime(date('Y-m-d H:i:s'));
                },
            ]
        ];
    }

    /**
     * After saving product feedback adding new answer
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->scenario == self::SCENARIO_CREATE) {
            $answerModel = new AnswerEntity();
            $data = [
                'created_by'   => Yii::$app->user->identity->getId(),
                'product_id'   => $this->product_id,
                'type'         => AnswerEntity::TYPE_NEW_PRODUCT_REPORT,
                'text'         => 'оставил отзыв о вашем товаре'
            ];

            $answerModel->addAnswer($data);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductEntity::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'user_id']);
    }

    /**
     * Method of getting product feedback count
     *
     * @param $productId
     * @return int|string
     */
    public function getProductFeedbackCount($productId)
    {
        $verifiedUserIds = UserEntity::find()->select('id')->where(['status' => UserEntity::STATUS_VERIFIED]);
        return ProductFeedbackEntity::find()->where(['product_id' => $productId, 'user_id' => $verifiedUserIds])->count();
    }

    /**
     * Method of getting average product rating
     *
     * @param $productId
     * @return int|mixed
     */
    public function getAverageProductRating($productId)
    {
        $verifiedUserIds = UserEntity::find()->select('id')->where(['status' => UserEntity::STATUS_VERIFIED]);
        $result = ProductFeedbackEntity::find()->select('AVG(rating) as rating')
            ->where(['product_id' => $productId, 'user_id' => $verifiedUserIds])->asArray()->one();

        return (isset($result['rating'])) ? $result['rating'] : 0;
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