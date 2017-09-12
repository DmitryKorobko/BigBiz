<?php
namespace backend\modules\shop\control\controllers\actions\product;

use common\models\{
    city\CityEntity, product\ProductEntity, product_price\ProductPriceEntity
};
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class UpdateAction
 *
 * @package backend\modules\shop\control\controllers\actions\product
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/shop/control/views/product/update';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update';
    }

    /**
     * Action for updating product
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function run($id)
    {
        /* @var  $modelCity CityEntity */
        $modelCity = new CityEntity();

        /* @var  $modelProduct ProductEntity.php */
        $modelProduct = $this->controller->findModel($id);
        $modelProduct->scenario = ProductEntity::SCENARIO_UPDATE;

        $modelProduct->towns = $modelProduct->cities;
        $postData = Yii::$app->request->post();

        /* @var  $modelPrice [ProductPriceEntity.php] */
        $modelPrice = $modelProduct->prices;

        /** Set sort column if shop does not set */
        if ($postData && empty($postData['ProductEntity']['sort'])) {
            /** @var  $lastProduct ProductEntity.php */
            $lastProduct = ProductEntity::find()->where(['<>', 'id', $modelProduct->id])
                ->andWhere(['user_id' => Yii::$app->user->identity->getId()])
                ->orderBy('sort desc')->limit(1)->one();
            $postData['ProductEntity']['sort'] = ++$lastProduct->sort;
        }

        if ($modelProduct->load($postData)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $oldPrices = ArrayHelper::map($modelPrice, 'id', 'id');
                $modelPrice = ProductPriceEntity::createMultiple();
                ProductEntity::loadMultiple($modelPrice, Yii::$app->request->post());
                $pricesToDelete = array_diff($oldPrices, array_filter(ArrayHelper::map($modelPrice, 'id', 'id')));

                $cities = CityEntity::createMultipleModels($postData['ProductEntity']['towns']);
                if ($this->controller->validateModels($modelProduct, $modelPrice, $modelCity)) {
                    $modelProduct->save();
                    $modelProduct->savePrices($modelPrice, $pricesToDelete);
                    $modelProduct->linkAll('cities', $cities, [], true, true);

                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('success', "Товар {$modelProduct->name} успешно отредактирован!");
                    return $this->controller->redirect('index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error',
                    "Произошла ошибка при редактировании товара - {$modelProduct->name}!");

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