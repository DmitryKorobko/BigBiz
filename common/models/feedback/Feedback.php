<?php

namespace common\models\feedback;

use yii\{
    behaviors\TimestampBehavior, data\ActiveDataProvider, db\ActiveRecord
};
use Yii;
use common\models\feedback\repositories\BackendFeedbackRepository;

/**
 * Class Feedback
 *
 * @package common\models\feedback
 *
 * @property integer $id
 * @property string $name
 * @property string $message
 * @property integer $user_id
 * @property string  $status
 * @property string  $cause_send
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $author_name
 */
class Feedback extends ActiveRecord
{
    use BackendFeedbackRepository;

    const APPLICATION_PROBLEM = 'APPLICATION_PROBLEM';
    const FUNCTIONAL_PROBLEM  = 'FUNCTIONAL_PROBLEM';
    const WISHES              = 'WISHES';

    const STATUS_READ   = 'READ';
    const STATUS_UNREAD = 'UNREAD';

    public $author_name;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%feedback}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'       => 'Тема',
            'message'    => 'Сообщение',
            'user_id'    => 'Автор',
            'status'     => 'Статус',
            'created_at' => 'Дата создания',
            'cause_send' => 'Причина отправки'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'user_id', 'cause_send'], 'required'],
            [['user_id'], 'integer'],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
            ['message', 'string'],
            [
                ['cause_send'],
                'in',
                'range' => self::validateEnumAccessValues(),
                'skipOnError' => false
            ],
            ['author_name', 'safe']
        ];
    }

    /**
     * behavior for auto additing created and updated time.
     * @return array
     */
    public function behaviors(): array
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
     * Method of getting list feedback of shop or user
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = self::find()->where(['user_id' => $params['user_id']]);
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Access ENUM validator. Checking value of access is in range ENUM values.
     *
     * @return array
     */
    protected static function validateEnumAccessValues(): array
    {
        return [
            self::APPLICATION_PROBLEM,
            self::FUNCTIONAL_PROBLEM,
            self::WISHES
        ];
    }
}
