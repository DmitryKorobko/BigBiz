<?php
namespace common\models\theme_user_like_show;

use common\models\answer\AnswerEntity;
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord
};
use Yii;

/**
 * Class ThemeUserLikeShowEntity
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $theme_id
 * @property integer $like
 * @property integer $show
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @package common\models\theme_user_like_show
 */
class ThemeUserLikeShowEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%theme_user_like_show}}';
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'theme_id'], 'required', 'on' => [ self::SCENARIO_CREATE ]],
            [['user_id', 'theme_id', 'like', 'show'], 'number'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * After saving like of theme, adding new answer
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
                'recipient_id' => $this->user_id,
                'theme_id'     => $this->theme_id,
                'type'         => AnswerEntity::TYPE_LIKE_THEME,
                'text'         => 'оценил вашу тему'
            ];

            $answerModel->addAnswer($data);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Method of checking user has showed them or has not
     *
     * @param $userId
     * @param $themeId
     * @return bool
     */
    public function isShowByUser($themeId, $userId)
    {
        $model = self::findOne(['user_id' => $userId, 'theme_id' => $themeId, 'show' => 1]);
        return ($model) ? true : false;
    }

    /**
     * Method of set `show` flag as true by userID
     *
     * @param $themeId
     * @param $userId
     */
    public function setShowFlagByUser($themeId, $userId)
    {
        $model = new self();
        $model->theme_id = $themeId;
        $model->user_id = $userId;
        $model->show = 1;
        $model->save();
    }

    /**
     * Method of set like or dislike theme by userID
     *
     * @param $themeId
     * @param $userId
     * @param $like
     * @return bool
     */
    public function setLikeOrDislikeThemeByUser($themeId, $userId, $like)
    {
        $model = self::findOne(['user_id' => $userId, 'theme_id' => $themeId]);
        if ($model) {
            $model->like = $like;
            return $model->save(false);
        }
        $model = new self();
        $model->scenario = self::SCENARIO_CREATE;
        $model->theme_id = $themeId;
        $model->user_id = $userId;
        $model->like = $like;
        return $model->save();
    }
}
