<?php
namespace backend\components;

use common\models\shop_profile\ShopProfileEntity;
use Yii;
use yii\base\Component;

/**
 * Class AlertCategory
 *
 * AlertCategory component
 * Output alert in all shop-user routes when
 * "category_end" unix time < "Yii::$app->params['categoryTimeBeforeAlert']" unix time.
 * You can set "categoryTimeBeforeAlert" in config\params.php
 *
 * @package backend\components
 */
class AlertCategory extends Component
{
    private $message = "Внимание! Срок категории закончится меньше чем 3 дня! 
        Обратитесь к администратору для продления категории.";

    /**
     * init() execute every time when shop-user call action in all controllers
     */
    public function init()
    {
        if (Yii::$app->user->can('shop')) {
            /** @var $model ShopProfileEntity */
            $model = ShopProfileEntity::find()
                ->where(['user_id' => Yii::$app->user->id])->one();

            if ($model) {
                $offset = $model->category_end - time();
                if ($offset > 0 && $offset < Yii::$app->params['categoryTimeBeforeAlert']) {
                    Yii::$app->getSession()->setFlash('warning',  $this->message);
                }
            }
        }
        parent::init();
    }
}
