<?php
namespace common\models\message;

use common\models\{
    message\repositories\RestMessageRepository, user\UserEntity, message\repositories\BackendMessageRepository,
    message\repositories\CommonMessageRepository, message_image\MessageImageEntity
};
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord
};

/**
 * Class MessageEntity
 *
 * @package common\models\message
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $recipient_id
 * @property integer $text
 * @property string  $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class MessageEntity extends ActiveRecord
{
    use RestMessageRepository, CommonMessageRepository, BackendMessageRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_MESSAGE_READ = 'READ';
    const STATUS_MESSAGE_UNREAD = 'UNREAD';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'created_by'   => 'Автор',
            'recipient_id' => 'Получатель',
            'text'         => 'Содержание сообщения',
            'image'        => 'Картинка',
            'status'       => 'Статус',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата изменения'
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['created_by', 'recipient_id', 'text'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['text'], 'string'],
            [['created_by', 'recipient_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(MessageImageEntity::className(), ['message_id' => 'id']);
    }
}
