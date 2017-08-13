<?php
namespace common\models\mobile_banner;

use common\behaviors\ImageBehavior;
use Yii;
use yii\{ behaviors\TimestampBehavior, data\ActiveDataProvider, db\ActiveRecord};
use common\models\settings\SettingsEntity;

/**
 * Class MobileBannerEntity
 * @package common\models\mobile_banner
 *
 * @property integer $id
 * @property string $image
 * @property integer $user_id
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class MobileBannerEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const IMAGE_MAX = 'mobile_banner_max_size';
    const IMAGE_MIN = 'mobile_banner_min_size';
    const PRICE     = 'mobile_banner_price';

    /** @var  $period_of_time */
    public $period_of_time;

    /** @var  $price */
    public $price;

    /** @var  $count_days */
    public $count_days;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%mobile_banner}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'image'       => 'Изображение',
            'price'       => 'Стоимость (грн)',
            'count_days'  => 'Количество дней',
            'user_id'     => 'Автор',
            'start_date'  => 'Дата начала отображения',
            'end_date'    => 'Дата завершения отображения',
            'status'      => 'Статус',
            'created_at'  => 'Дата создания',
            'updated_at'  => 'Дата последнего обновления'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['start_date', 'end_date'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['user_id'], 'integer'],
            [['image'], 'file', 'skipOnEmpty' => false, 'on' => [self::SCENARIO_CREATE]],
            [['image'], 'file', 'skipOnEmpty' => true, 'on' => [self::SCENARIO_UPDATE]],
            [
                'image',
                'file',
                'maxSize' => SettingsEntity::findOne(['key' => 'mobile_banner_max_size'])->value * 1024
                    ?? Yii::$app->params['mobile_banner_max_size'],
                'minSize' => SettingsEntity::findOne(['key' => 'mobile_banner_min_size'])->value * 1024
                    ?? Yii::$app->params['mobile_banner_min_size'],
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['created_at', 'updated_at', 'user_id'], 'safe']
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->scenario === self::SCENARIO_CREATE || $this->scenario === self::SCENARIO_UPDATE) {
                $this->start_date = strtotime($this->start_date);
                $this->end_date = strtotime($this->end_date);
                return true;
            }
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
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
                    return strtotime(date('Y-m-d H:i:s'));
                }
            ],
            'ImageBehavior' => [
                'class'           => ImageBehavior::className(),
                'attributeName'   => 'image',
                'savePath'        => "@webroot/images/uploads/user-{$userId}/mobile_banner",
                'saveWithUrl'     => true,
                'url'             => "/admin/images/uploads/user-{$userId}/mobile_banner/"
            ]
        ];
    }


    /**
     * Method of getting list mobile banners
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $shopId = isset($params['shop_id']) ? $params['shop_id'] : Yii::$app->user->identity->getId();
        $query = self::find()->where(['user_id' => $shopId]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => [
                'pageSize' => isset($params['limit']) ? $params['limit'] : 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Turn on or off banner
     *
     * @return bool
     */
    public function setStatus(): bool
    {
        if ($this->status) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }

        if ($this->save()) {
            return true;
        }
        return false;
    }

    /**
     * Method of getting active mobile banners
     *
     * @return array|ActiveRecord[]
     */
    public function getListBanners(): array
    {
        return self::find()
            ->select(['id', 'image'])
            ->where(['status' => 1])
            ->andWhere(['>', 'end_date', time()])
            ->andWhere(['<', 'start_date', time()])
            ->orderBy(new Expression('rand()'))
            ->all();
    }
}
