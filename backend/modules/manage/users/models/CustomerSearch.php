<?php

namespace backend\modules\manage\users\models;

use backend\models\BackendUserEntity;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Class CustomerSearch
 *
 * @package backend\modules\manage\users\models
 */
class CustomerSearch extends BackendUserEntity
{
    public $datetime_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'string'],
            [['status', 'status_online', 'datetime_range'], 'safe']
        ];
    }

    /**
     * Method of getting customer list
     *
     * @param array $params
     * @param $role
     * @return ActiveDataProvider
     */
    public function search($params, $role)
    {
        $query = BackendUserEntity::find()
            ->select([ 'user.*'])
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => $role, 'user.is_deleted' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'email',
                'status',
                'status_online',
                'created_at'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status'        => $this->status,
            'status_online' => $this->status_online
        ]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        if ($this->datetime_range && strpos($this->datetime_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->datetime_range);
            $query->andFilterWhere(['between', 'user.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
