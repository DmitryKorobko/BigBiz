<?php
namespace backend\modules\shop\control\controllers\actions\product;

use common\models\{
    city\CityEntity, product\ProductEntity, product_price\ProductPriceEntity
};
use Yii;
use yii\base\Action;
use yii\base\Exception;

/**
 * Class CreateAction
 *
 * @package backend\modules\shop\control\controllers\actions\product
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/shop/control/views/product/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * Action for creating product
     *
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /* @var  $modelCity CityEntity */
        $modelCity = new CityEntity();

        /* @var  $modelItem ProductEntity */
        $modelProduct = new ProductEntity();
        $modelProduct->scenario = ProductEntity::SCENARIO_CREATE;
        $postData = Yii::$app->request->post();

        /** @var  $lastProduct ProductEntity.php */
        $lastProduct = ProductEntity::find()->where(['user_id' => Yii::$app->user->identity->getId()])
            ->orderBy('sort desc')->limit(1)->one();
        /** Set sort column if shop does not set */
        if ($postData && empty($postData['ProductEntity']['sort']) && $lastProduct) {
            $postData['ProductEntity']['sort'] = ++$lastProduct->sort;
        }

        if ($modelProduct->load($postData)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $modelPrice = ProductPriceEntity::createMultiple();
                ProductEntity::loadMultiple($modelPrice, Yii::$app->request->post());

                $cities = CityEntity::createMultipleModels($postData['ProductEntity']['towns']);
                if ($this->controller->validateModels($modelProduct, $modelPrice, $modelCity)) {
                    $modelProduct->save();
                    $modelProduct->savePrices($modelPrice);
                    $modelProduct->linkAll('cities', $cities, [], true, true);

                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('success', "Товар {$modelProduct->name} добавлен успешно!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) { return $e->getMessage();
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error',
                    "Произошла ошибка при добавлении товара - {$modelProduct->name} !");
                return $this->controller->redirect('index');
            }
        }

        return $this->controller->render($this->view, [
            'product'  => $modelProduct,
            'city'     => $modelCity,
            'price'    => (empty($modelPrice)) ? [new ProductPriceEntity] : $modelPrice
        ]);
    }
}