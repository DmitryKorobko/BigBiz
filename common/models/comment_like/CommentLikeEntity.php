<?php
namespace common\models\comment_like;

use common\models\answer\AnswerEntity;
use yii\{
    behaviors\TimestampBehavior, db\Exception as ExceptionDb, db\ActiveRecord, web\ServerErrorHttpException
};
use Yii;

/**
 * Class CommentLikeEntity
 *
 * @package common\models\comment_like
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property integer $like
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommentLikeEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment_like}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['like', 'comment_id', 'user_id'], 'required', 'on' => [ self::SCENARIO_CREATE ]],
            [['comment_id', 'like', 'user_id'], 'integer']
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
     * After saving like of comment, adding new answer
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
                'theme_id'     => $this->comment_id,
                'type'         => AnswerEntity::TYPE_LIKE_COMMENT,
                'text'         => 'оценил ваш комментарий'
            ];

            $answerModel->addAnswer($data);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    public function validationExceptionFirstMessage($modelErrors)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
    }

    /**
     * Method of liking comment by user
     *
     * @param $commentId
     * @param $like
     * @throws ServerErrorHttpException
     * @return array
     */
    public function likeCommentByUser($commentId, $like)
    {
        $model = self::findOne(['user_id' => Yii::$app->user->identity->getId(), 'comment_id' => $commentId]);
        if ($model && $model->delete()) {
            Yii::$app->getResponse()->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Like удален'
            ];
        }

        $model = new self();
        $model->scenario = self::SCENARIO_CREATE;
        $model->comment_id = $commentId;
        $model->user_id = Yii::$app->user->identity->getId();
        $model->like = $like;
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201, 'Created');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Like успешно добавлен'
            ];
        }
        
        throw new ServerErrorHttpException('Произошла ошибка при добавлении like комментария.');
    }

    /**
     * Method of getting count likes of comment
     *
     * @param $commentId
     * @return int|string
     */
    public function getCountCommentLike($commentId)
    {
        return CommentLikeEntity::find()->where(['comment_id' => $commentId, 'like' => 1])->count();
    }

    /**
     * Method of checking to like or not user comment
     *
     * @param $commentId
     * @return bool
     */
    public function isLikedCommentByUser($commentId)
    {
        $result = CommentLikeEntity::findOne([
            'comment_id' => $commentId,
            'like'       => 1,
            'user_id'    => ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest')
        ]);

        return ($result) ? true : false;
    }
}
