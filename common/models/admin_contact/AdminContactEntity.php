<?php
namespace common\models\admin_contact;

use yii\{
    behaviors\TimestampBehavior, db\ActiveRecord
};
use common\{
    models\user\UserEntity, models\admin_contact\repositories\BackendAdminContactRepository, behaviors\ImageBehavior
};
use Yii;
use cornernote\linkall\LinkAllBehavior;

/**
 * Class AdminContactEntity
 *
 * @package common\models\admin_contact
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $nickname
 * @property string $avatar
 * @property integer $is_boss
 * @property string $skype
 * @property string $viber
 * @property string $jabber
 * @property string $vipole
 * @property string $telegram
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdminContactEntity extends ActiveRecord
{
    use BackendAdminContactRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%admin_contact}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nickname' => 'Никнейм',
            'avatar'   => 'Аватарка',
            'is_boss'  => 'Главный администратор',
            'skype'    => 'Skype',
            'viber'    => 'Viber',
            'jabber'   => 'Jabber',
            'vipole'   => 'Vipole',
            'telegram' => 'Telegram'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required', 'on' => [ self::SCENARIO_CREATE ]],
            [['is_boss'], 'integer'],
            [['nickname', 'jabber', 'vipole', 'skype', 'viber', 'telegram'], 'string', 'length' => [3, 255]],
            [['user_id'], 'number', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['jabber', 'vipole', 'created_at', 'updated_at', 'skype', 'viber', 'telegram'], 'safe'],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
        ];
    }

    /**
     * behavior for auto additing created and updated time.
     * @return array
     */
    public function behaviors()
    {
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');
        return [
            [
                'class' => LinkAllBehavior::className()
            ],
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return time();
                },
            ],
            'ImageBehavior' => [
                'class'         => ImageBehavior::className(),
                'attributeName' => 'avatar',
                'savePath'      => "@webroot/images/uploads/user-{$userId}/profile",
                'saveWithUrl'   => true,
                'url'           => "/admin/images/uploads/user-{$userId}/profile/"
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserEntity::className(), ['id' => 'user_id'])->select(['email', 'status']);
    }
}
