<?php

namespace backend\modules\manage\users\models;

use yii\data\ActiveDataProvider;
use common\models\{
    shop_profile\ShopProfileEntity, user\UserEntity
};

/**
 * Class ShopSearch
 *
 * @package backend\modules\manage\users\models
 */
class ShopSearch extends ShopProfileEntity
{
    public $email;
    public $created_date_range;
    public $category_date_range;

    public function rules(): array
    {
        return [
            [['email', 'skype', 'name'], 'string'],
            [['created_date_range', 'category_date_range'], 'safe']

        ];
    }

    /**
     * Method of getting shop profile list
     *
     * @param array $params
     * @param $role
     * @return ActiveDataProvider
     */
    public function search($params, $role = UserEntity::ROLE_SHOP)
    {
        $query = ShopProfileEntity::find()
            ->select(['user.id as id', 'user.email as email',
                'shop_profile.name', 'shop_profile.skype', 'shop_profile.category_end', 'shop_profile.created_at',
                'shop_profile.user_id'])
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = shop_profile.user_id')
            ->where(['auth_assignment.item_name' => $role])
            ->leftJoin('user', 'user.id = shop_profile.user_id');

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc'     => ['name' => SORT_ASC],
                    'desc'    => ['name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'email',
                'skype',
                'category_end',
                'created_at'
            ]
        ]);
        $this->load($params);

        if (isset($params['ShopSearch'])) {
            $query
                ->andFilterWhere(['like', 'email', $params['ShopSearch']['email']])
                ->andFilterWhere(['like', 'skype', $params['ShopSearch']['skype']])
                ->andFilterWhere(['like', 'name', $params['ShopSearch']['name']]);
        }

        if ($this->created_date_range && strpos($this->created_date_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->created_date_range);
            $query->andFilterWhere(['between', 'user.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        if ($this->category_date_range && strpos($this->category_date_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->category_date_range);
            $query->andFilterWhere(['between', 'category_end', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}