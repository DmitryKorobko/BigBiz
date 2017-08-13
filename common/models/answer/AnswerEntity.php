<?php
namespace common\models\answer;

use common\models\{
    answer\repositories\RestAnswerRepository, user\UserEntity, product\ProductEntity, comment\CommentEntity,
    theme\ThemeEntity, answer\repositories\CommonAnswerRepository, answer\repositories\BackendAnswerRepository
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord
};

/**
 * Class AnswerEntity
 *
 * @package common\models\answer
 *
 * @property integer $id
 * @property string  $type
 * @property integer $recipient_id
 * @property integer $created_by
 * @property integer $product_id
 * @property integer $theme_id
 * @property integer $comment_id
 * @property string  $text
 * @property string  $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class AnswerEntity extends ActiveRecord
{
    use RestAnswerRepository, CommonAnswerRepository, BackendAnswerRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const TYPE_LIKE_THEME             = 'LIKE_THEME';
    const TYPE_LIKE_COMMENT           = 'LIKE_COMMENT';
    const TYPE_REPLY_COMMENT          = 'REPLY_COMMENT';
    const TYPE_NEW_PRODUCT_REPORT     = 'NEW_PRODUCT_REPORT';
    const TYPE_NEW_THEME_COMMENT      = 'NEW_THEME_COMMENT';
    const TYPE_NEW_PRODUCT_COMMENT    = 'NEW_PRODUCT_COMMENT';
    const TYPE_NEW_USER_REPUTATION    = 'NEW_USER_REPUTATION';
    const TYPE_NEW_SHOP_REVIEW        = 'NEW_SHOP_REVIEW';
    const TYPE_THEME_WAS_VERIFICATION = 'THEME_WAS_VERIFIED';

    const STATUS_READ   = 'READ';
    const STATUS_UNREAD = 'UNREAD';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%answer}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'                         => 'Идентификатор ответа',
            'type'                       => 'Тип ответа',
            'recipient_id'               => 'Адресат ответа',
            'created_by'                 => 'Автор ответа',
            'product_id'                 => 'Идентификатор товара',
            'theme_id'                   => 'Идентификатор темы',
            'comment_id'                 => 'Идентификатор комментария',
            'text'                       => 'Текст ответа',
            'status'                     => 'Статус просмотра ответа',
            'created_at'                 => 'Дата создания',
            'updated_at'                 => 'Дата последнего обновления'
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['type', 'recipient_id', 'created_by', 'text'],
                'required',
                'on' => [self::SCENARIO_CREATE]
            ],
            [
                [
                    'recipient_id',
                    'product_id',
                    'theme_id',
                    'comment_id',
                    'created_by'
                ],
                'integer'
            ]
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
    public function getRecipient(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(ProductEntity::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme(): ActiveQuery
    {
        return $this->hasOne(ThemeEntity::className(), ['id' => 'theme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment(): ActiveQuery
    {
        return $this->hasOne(CommentEntity::className(), ['id' => 'comment_id']);
    }
}
