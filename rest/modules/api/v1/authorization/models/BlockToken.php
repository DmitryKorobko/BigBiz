<?php

namespace rest\modules\api\v1\authorization\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table 'block_token'.
 *
 * @property string $id
 * @property string $token
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $expired_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package rest\modules\api\v1\authorization\models
 */
class BlockToken extends ActiveRecord
{
    /**
     * Scenario
     */
    const SCENARIO_CREATE_BLOCK = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_token}}';
    }

    /**
     * @inheritdoc
     */
    public $primaryKey = 'token';

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'token',
            'user_id',
            'created_at',
            'expired_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE_BLOCK] = [
            'id',
            'token',
            'user_id',
            'created_at',
            'expired_at',
        ];

        return $scenarios;
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
                'updatedAtAttribute' => null,
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
            [['user_id', 'token', 'expired_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['expired_at'], 'integer'],
            [['user_id', 'token', 'expired_at'], 'required', 'on' => self::SCENARIO_CREATE_BLOCK],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'user_id'    => Yii::t('app', 'User ID'),
            'token'      => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created at'),
            'expired_at' => Yii::t('app', 'Expired at'),
        ];
    }

    /**
     * @inheritdoc string
     */
    public function formName()
    {
        return '';
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'token' => $this->token,
            'id' => $this->id,
            'created_at' => $this->id,
            'expired_at' => $this->id,
        ]);

        return $dataProvider;
    }
}
