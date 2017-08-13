<?php
namespace common\models\user_theme_favorite;

use Yii;
use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord
};
use common\models\theme\ThemeEntity;
use \cornernote\linkall\LinkAllBehavior;

/**
 * Class UserThemeFavoriteEntity
 *
 * @package common\models\user_theme_favorite
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $theme_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserThemeFavoriteEntity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%user_theme_favorite}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => Yii::t('app', 'id'),
            'user_id'    => Yii::t('app', 'Идентификатор пользователя'),
            'theme_id'   => Yii::t('app', 'Идентификатор темы'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата последнего обновления')
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id', 'theme_id'], 'required'],
            [['user_id', 'theme_id'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [
                ['user_id', 'theme_id'],
                'validateFavoriteTheme',
                'skipOnError' => false
            ],
            [
                ['theme_id'],
                'isExistingTheme',
                'skipOnError' => false
            ]
        ];
    }

    /**
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
                }
            ],
            [
                'class' => LinkAllBehavior::className()
            ]
        ];
    }

    /**
     * FavoriteTheme validator. Checking if the user left add this theme in favorite.
     *
     * @return bool
     */
    public function validateFavoriteTheme(): bool
    {
        $themeFavorite = self::find()
            ->select(['user_theme_favorite.id', 'user_theme_favorite.user_id', 'user_theme_favorite.theme_id'])
            ->where(['theme_id' => $this->theme_id, 'user_id' =>  Yii::$app->user->identity->getId()])
            ->one();
        if (!empty($themeFavorite)) {
            $this->addError('created_by',
                Yii::t('app', 'Данная тема уже добавлена в ваш список избранных'));
            return false;
        } else {
            return true;
        }
    }

    /**
     * isExistingTheme validator. Checking if theme not exist.
     *
     * @return bool
     */
    public function isExistingTheme(): bool
    {
        $postData = Yii::$app->getRequest()->getBodyParams();

        $theme = ThemeEntity::findOne(['id' => $postData['theme_id']]);
        if (!$theme) {
            $this->addError('theme_id',
                Yii::t('app', 'Тема не найдена'));
            return false;
        } else {
            return true;
        }
    }
}