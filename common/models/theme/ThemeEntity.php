<?php

namespace common\models\theme;

use Yii;
use common\behaviors\ImageBehavior;
use \cornernote\linkall\LinkAllBehavior;
use yii\{
    behaviors\TimestampBehavior, db\ActiveQuery, db\ActiveRecord
};
use common\models\{
    child_category_section\ChildCategorySectionEntity, shop_profile\ShopProfileEntity,
    theme\repositories\BackendThemeRepository, theme\repositories\RestThemeRepository, user\UserEntity,
    theme\repositories\FrontendThemeRepository, theme\repositories\CommonThemeRepository, settings\SettingsEntity
};

/**
 * Class ThemeEntity
 *
 * @package common\models
 *
 * @property integer $id
 * @property integer $category_id
 * @property string  $name
 * @property string  $description
 * @property string  $image
 * @property integer $view_count
 * @property integer $comments_count
 * @property integer $new_comments_count
 * @property integer $user_id
 * @property integer $date_of_publication
 * @property integer $sort
 * @property string  $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $user_name
 */
class ThemeEntity extends ActiveRecord
{
    use BackendThemeRepository, RestThemeRepository, FrontendThemeRepository, CommonThemeRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const IMAGE_MAX = 'theme_image_max_size';
    const IMAGE_MIN = 'theme_image_min_size';

    const STATUS_VERIFIED   = 'VERIFIED';
    const STATUS_UNVERIFIED = 'UNVERIFIED';
    const STATUS_REJECTED   = 'REJECTED';

    public $theme_created_range;
    public $user_name;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%theme}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'                  => '#',
            'category_id'         => 'Категория для темы',
            'name'                => 'Название',
            'description'         => 'Контент',
            'image'               => 'Изображение',
            'view_count'          => 'Кол-во просмотров',
            'comments_count'      => 'Кол-во комментариев',
            'new_comments_count'  => 'Кол-во непрочитанных комментариев',
            'user_id'             => 'Магазин',
            'sort'                => 'Сортировка',
            'status'              => 'Статус',
            'date_of_publication' => 'Дата публикации',
            'created_at'          => 'Дата создания',
            'updated_at'          => 'Дата последнего обновления',
            'user_name'           => 'Пользователь'
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'description'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['view_count', 'comments_count', 'sort'], 'number'],
            [['comments_count'], 'default', 'value' => 0],
            [['new_comments_count'], 'default', 'value' => 0],
            [['view_count'], 'default', 'value' => 0],
            [['sort'], 'default', 'value' => 1],
            ['status', 'default', 'value' => self::STATUS_UNVERIFIED],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
            [
                ['image'],
                'file',
                'maxSize' => SettingsEntity::findOne(['key' => 'theme_image_max_size'])->value * 1024
                    ?? Yii::$app->params['theme_image_max_size'],
                'minSize' => SettingsEntity::findOne(['key' => 'theme_image_min_size'])->value * 1024
                    ?? Yii::$app->params['theme_image_min_size'],
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                [
                    'category_id', 'created_at', 'updated_at', 'date_of_publication', 'name', 'theme_created_range', 'user_name'
                ],
                'safe'
            ],
            [['name', 'description'], 'string']
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
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
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_of_publication',
                'value'              => function () {
                    return time();
                }
            ],
            [
                'class' => LinkAllBehavior::className()
            ],
            'ImageBehavior' => [
                'class'           => ImageBehavior::className(),
                'attributeName'   => 'image',
                'savePath'        => "@webroot/images/uploads/user-{$userId}/theme",
                'saveWithUrl'     => true,
                'url'             => "/admin/images/uploads/user-{$userId}/theme/",
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'user_id']);
    }

    /**
     * @return mixed
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(ChildCategorySectionEntity::className(), ['id' => 'category_id']);
    }

    /**
     * @return mixed
     */
    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(ShopProfileEntity::className(), ['user_id' => 'user_id'])->select(['name']);
    }

}