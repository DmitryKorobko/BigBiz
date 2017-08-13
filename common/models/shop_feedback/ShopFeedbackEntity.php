<?php
namespace common\models\shop_feedback;

use common\models\{
    shop_feedback\repositories\BackendShopFeedbackRepository, shop_feedback\repositories\RestShopFeedbackRepository,
    shop_profile\ShopProfileEntity, user\UserEntity, answer\AnswerEntity,
    shop_feedback\repositories\CommonShopFeedbackRepository
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord, db\Exception as ExceptionDb,
    web\NotFoundHttpException
};

/**
 * Class ShopFeedbackEntity
 *
 * @package common\models\shop_feedback
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $created_by
 * @property string  $status
 * @property integer $product_rating
 * @property integer $operator_rating
 * @property integer $reliability_rating
 * @property integer $marker_rating
 * @property double $average_rating
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShopFeedbackEntity extends ActiveRecord
{
    use RestShopFeedbackRepository, BackendShopFeedbackRepository, CommonShopFeedbackRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_READ   = 'READ';
    const STATUS_UNREAD = 'UNREAD';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%shop_feedback}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'                         => 'id',
            'shop_id'                    => 'Магазин',
            'created_by'                 => 'Автор отзыва',
            'status'                     => 'Статус',
            'product_rating'             => 'Качество товара',
            'operator_rating'            => 'Качество работы оператра',
            'reliability_rating'         => 'Надежность магазина',
            'marker_rating'              => 'Качество доставки',
            'average_rating'             => 'Средний рейтинг',
            'created_at'                 => 'Дата создания',
            'updated_at'                 => 'Дата последнего обновления'
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['product_rating', 'operator_rating', 'reliability_rating', 'marker_rating', 'shop_id', 'created_by'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                [
                    'product_rating',
                    'operator_rating',
                    'reliability_rating',
                    'marker_rating',
                    'shop_id',
                    'created_by'
                ],
                'integer'
            ],
            [
                ['shop_id', 'created_by'],
                'validateReviewer',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE]
            ],
            [
                ['shop_id', 'created_by'],
                'isExistingReview',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_UPDATE]
            ],
            [
                ['shop_id'],
                'isExistingShop',
                'skipOnError' => false,
                'on'          => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
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
    public function getShop(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'created_by']);
    }

    /**
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    private function validationExceptionFirstMessage($modelErrors): bool
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
    }

     /**
     * Reviewer validator. Checking if the user left a review about this shop.
     *
     * @return bool
     */
    public function validateReviewer(): bool
    {
        $review = self::find()
            ->select(['shop_feedback.id', 'shop_feedback.shop_id', 'shop_feedback.created_by'])
            ->where(['shop_feedback.shop_id' => $this->shop_id, 'shop_feedback.created_by' => $this->created_by])
            ->asArray()
            ->one();
        if(!empty($review)){
            $this->addError('created_by',
                Yii::t('app', 'Вы уже добавляли отзыв об этом магазине, но Вы можете его изменить'));
            return false;
        } else {
            return true;
        }
    }

    /**
     * Method of getting average shop rating by all categories
     *
     * @param $userId
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function getAverageShopRating($userId)
    {
        $verifiedUserIds = UserEntity::find()->select('id')->where(['status' => UserEntity::STATUS_VERIFIED]);
        $reviews = ShopFeedbackEntity::find()->where(['shop_id' => $userId, 'created_by' => $verifiedUserIds])->all();

        $count = 0;
        if ($reviews) {
            $sumProductRating = 0; $sumReliabilityRating = 0;
            $sumOperatorRating = 0; $sumMarkerRating = 0; $sumAverageRating = 0;

            /** @var  $item ShopFeedbackEntity.php */
            foreach ($reviews as $item) {
                $count++;
                $sumProductRating += $item->product_rating;
                $sumReliabilityRating += $item->reliability_rating;
                $sumOperatorRating += $item->operator_rating;
                $sumMarkerRating += $item->marker_rating;
                $sumAverageRating += $item->average_rating;
            }

            return [
                'average_product_rating'     => round($sumProductRating / $count, 1),
                'average_reliability_rating' => round($sumReliabilityRating / $count, 1),
                'average_operator_rating'    => round($sumOperatorRating / $count, 1),
                'average_marker_rating'      => round($sumMarkerRating / $count, 1),
                'average_rating'             => round($sumAverageRating / $count, 1)
            ];
        }
        return false;
    }

    /**
     * Method of calculating average shop rating
     *
     * @param $data
     * @return float|int
     */
    public function calculateAverageRating($data)
    {
        return ($data['product_rating'] + $data['operator_rating']
                + $data['marker_rating'] + $data['reliability_rating']) / 4;
    }

    /**
     * isExistingReview validator. Checking if review not exist.
     *
     * @return bool
     */
    public function isExistingReview(): bool
    {
        $review = ShopFeedbackEntity::findOne([
            'created_by' => (!isset(Yii::$app->request->post()['created_by']))
                ? Yii::$app->user->identity->getId() : Yii::$app->request->post()['created_by'],
            'shop_id' => Yii::$app->request->post()['shop_id']]);
        if (!$review) {
            $this->addError('created_by',
                Yii::t('app', 'Отзыв не найден'));
            return false;
        } else {
            return true;
        }
    }

    /**
     * isExistingShop validator. Checking if shop not exist.
     *
     * @return bool
     */
    public function isExistingShop(): bool
    {
        $shop = ShopProfileEntity::findOne([
            'user_id' => Yii::$app->request->post()['shop_id']]);
        if (!$shop) {
            $this->addError('user_id',
                Yii::t('app', 'Магазин не найден'));
            return false;
        } else {
            return true;
        }
    }

    /**
     * After saving review, adding new answer
     *
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->scenario == self::SCENARIO_CREATE) {
            $answerModel = new AnswerEntity();
            $data = [
                'created_by'   => (!isset(Yii::$app->request->post()['created_by']))
                    ? Yii::$app->user->identity->getId() : Yii::$app->request->post()['created_by'],
                'recipient_id' => $this->shop_id,
                'type'         => AnswerEntity::TYPE_NEW_SHOP_REVIEW,
                'text'         => 'добавил отзыв о вашем магазине'
            ];

            $answerModel->addAnswer($data);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}