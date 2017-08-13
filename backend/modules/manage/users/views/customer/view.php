<?php

/* @var common\models\child_category_section\ChildCategorySectionEntity; */
/* @var $profile \common\models\user_profile\UserProfileEntity */
/* @var $shopReviews  \common\models\shop_feedback\ShopFeedbackEntity */
/* @var $productReviews  \common\models\product_feedback\ProductFeedbackEntity */
/* @var $modelTheme \common\models\theme\ThemeEntity */
/* @var $comment  \common\models\comment\CommentEntity */
/* @var $dataProviderTheme \yii\data\ActiveDataProvider */
/* @var $dataProviderProduct \yii\data\ActiveDataProvider */
/* @var $dataProviderComments \yii\data\ActiveDataProvider */
/* @var $dataProviderFeedback \yii\data\ActiveDataProvider */
/* @var $dataProviderStatistics \yii\data\ActiveDataProvider */

$this->title = 'Блогеры/Покупатели';
$this->params['breadcrumbs'][] = ['label' => 'Блогеры/Покупатели', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить ' . $profile->nickname;
$user = \common\models\user\UserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);

?>

<div class="personal-info-update">
    <?php if ($profile->nickname) : ?>
        <?= $this->render('partial_view/personal_user_info', ['profile' => $profile], true); ?>
        <div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#theme">Темы</a></li>
                <li><a data-toggle="tab" href="#comments">Комментарии</a></li>
                <li><a data-toggle="tab" href="#feedbacks">Обратная связь</a></li>
                <li><a data-toggle="tab" href="#shop-reviews">Отзывы о магазинах</a></li>
                <li><a data-toggle="tab" href="#product-reviews">Отзывы о продуктах</a></li>
                <li><a data-toggle="tab" href="#statistics">Статистика пользователя</a></li>
            </ul>
            <div class="tab-content"  style="background-color:#ffffff; border: 1px solid #f0f0f0; padding: 5px">
                <?= $this->render('partial_view/list_user_themes', [
                        'dataProviderTheme' => $dataProviderTheme, 'modelTheme' => $modelTheme
                ], true); ?>
                <?= $this->render('partial_view/user_comments', ['dataProviderComments' => $dataProviderComments,
                     'comment' => $comment, 'profile' => $profile], true); ?>
                <?= $this->render('partial_view/user_feedback', [
                        'dataProviderFeedback' => $dataProviderFeedback
                ], true); ?>
                <?= $this->render('partial_view/user_shops_reviews', [
                        'shopFeedbackProvider' => $shopFeedbackProvider, 'shopFeedback' => $shopFeedback
                ], true); ?>
                <?= $this->render('partial_view/user_products_reviews', [
                        'productFeedbackProvider' => $productFeedbackProvider, 'productFeedback' => $productFeedback
                ], true); ?>
                <?= $this->render('partial_view/user_statistics', [
                        'dataProviderStatistics' => $dataProviderStatistics
                ], true); ?>
            </div>
        </div>
    <?php endif; ?>
</div>