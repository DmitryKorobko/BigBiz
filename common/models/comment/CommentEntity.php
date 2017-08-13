<?php
namespace common\models\comment;

use common\models\{
    answer\AnswerEntity, comment\repositories\RestCommentRepository, comment\repositories\CommonCommentRepository,
    theme\ThemeEntity, user\UserEntity, comment_image\CommentImageEntity
};
use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord, db\Query
};

/**
 * Class CommentEntity
 *
 * @package common\models\comment
 *
 * @property integer $id
 * @property string  $text
 * @property integer $theme_id
 * @property integer $created_by
 * @property integer $recipient_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommentEntity extends ActiveRecord
{
    use CommentRepository, RestCommentRepository, CommonCommentRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_UNREAD = 'UNREAD';
    const STATUS_READ   = 'READ';

    public $created_date_range;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
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
                ['text', 'theme_id'],  'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
            ],
            [['text'], 'string'],
            [
                ['created_at', 'updated_at', 'created_by', 'recipient_id', 'theme_id', 'status', 'created_date_range'],
                'safe'
            ],
            [
                ['theme_id'], 'exist',
                'skipOnError'     => false,
                'targetClass'     => ThemeEntity::className(),
                'targetAttribute' => ['theme_id' => 'id'],
            ],
            [
                ['created_by'], 'exist',
                'skipOnError'     => false,
                'targetClass'     => UserEntity::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => '#',
            'text'         => 'Текст',
            'theme_id'     => 'Идентификатор темы',
            'created_by'   => 'Опубликовано',
            'recipient_id' => 'Кому адресован',
            'status'       => 'Статус',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата изменения'
        ];
    }

    /**
     * Before saving model we're checking scenario and adding
     * to update index current shop id,
     * changing comments status if scenario default
     * or change counters values if we had new comment
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->scenario == CommentEntity::SCENARIO_CREATE) {
                $this->created_by = Yii::$app->user->getId();

                /* @var $theme ThemeEntity */
                $theme = ThemeEntity::find()->where(['id' => $this->theme_id])->one();
                $theme->scenario = ThemeEntity::SCENARIO_UPDATE;
                $theme->comments_count++;

                if ($theme->save()) {
                    return true;
                }
            }

            if ($this->scenario == CommentEntity::SCENARIO_UPDATE) {
                return true;
            }

            if ($this->scenario == CommentEntity::SCENARIO_DEFAULT) {
                return true;
            }
        }

        return false;
    }

    /**
     * Before delete comment we must decrease "comments_count" field
     * in "theme" which remove a comment
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $theme = ThemeEntity::findOne($this->theme_id);
            $theme->scenario = ThemeEntity::SCENARIO_UPDATE;
            if ($theme->comments_count > 0) {
                $theme->comments_count--;
            }

            if ($theme->save()) {
                return true;
            }
        }

        return false;
    }

    /**
     * After deleting model we're remove comment from db
     */
    public function afterDelete()
    {
        if (parent::afterDelete()) {
            (new Query)->createCommand()
                ->delete('comment', ['id' => $this->id])
                ->execute();
        }
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

    public function getTheme()
    {
        return $this->hasOne(ThemeEntity::className(), ['id' => 'theme_id']);
    }

    /**
     * @return bool
     * @param $createdAt
     */
    public function acceptCommentUpdate($createdAt): bool
    {
        if (time() - $createdAt <= Yii::$app->params['timeToCommentUpdate']) {
            return false;
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(CommentImageEntity::className(), ['comment_id' => 'id']);
    }
}
