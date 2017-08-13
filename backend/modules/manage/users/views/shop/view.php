<?php

/* @var common\models\child_category_section\ChildCategorySectionEntity; */
/* @var $profile \common\models\shop_profile\ShopProfileEntity */
/* @var $shopReviews  \common\models\shop_feedback\ShopFeedbackEntity */
/* @var $rating  \common\models\shop_feedback\ShopFeedbackEntity */
/* @var $modelTheme \common\models\theme\ThemeEntity */
/* @var $modelProduct \common\models\product\ProductEntity */
/* @var $comment  \common\models\comment\CommentEntity */
/* @var $dataProviderCategory \yii\data\ActiveDataProvider */
/* @var $dataProviderTheme \yii\data\ActiveDataProvider */
/* @var $dataProviderProduct \yii\data\ActiveDataProvider */
/* @var $dataProviderWebsiteBanner \yii\data\ActiveDataProvider */
/* @var $dataProviderMobileBanner \yii\data\ActiveDataProvider */
/* @var $dataProviderComments \yii\data\ActiveDataProvider */
/* @var $dataProviderFeedback \yii\data\ActiveDataProvider */

$this->title = 'Назначить категорию магазину: ' . $profile->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить ' . $profile->name;
$user = \common\models\user\UserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);

?>

<div class="personal-info-update">
    <?php if ($profile->name) : ?>
        <?= $this->render('partial_view/personal_shop_info', ['rating' => $rating, 'profile' => $profile], true); ?>
        <div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#category">Аккаунт</a></li>
                <li><a data-toggle="tab" href="#theme">Темы</a></li>
                <li><a data-toggle="tab" href="#product">Товары</a></li>
                <li><a data-toggle="tab" href="#website_banner">Баннеры сайта</a></li>
                <li><a data-toggle="tab" href="#mobile_banner">Баннеры моб. приложения</a></li>
                <li><a data-toggle="tab" href="#comments">Комментарии</a></li>
                <li><a data-toggle="tab" href="#feedbacks">Обратная связь</a></li>
                <li><a data-toggle="tab" href="#reviews">Отзывы о магазине</a></li>
            </ul>
            <div class="tab-content"  style="background-color:#ffffff; border: 1px solid #f0f0f0; padding: 5px">
                <?= $this->render('partial_view/shop_account', ['profile' => $profile], true); ?>
                <?= $this->render('partial_view/list_shop_themes', ['dataProviderTheme' => $dataProviderTheme, 'modelTheme' => $modelTheme], true); ?>
                <?= $this->render('partial_view/list_shop_products', ['dataProviderProduct' => $dataProviderProduct, 'modelProduct' => $modelProduct], true); ?>
                <?= $this->render('partial_view/shop_website_banner', ['dataProviderWebsiteBanner' => $dataProviderWebsiteBanner], true); ?>
                <?= $this->render('partial_view/shop_mobile_banner', ['dataProviderMobileBanner' => $dataProviderMobileBanner], true); ?>
                <?= $this->render('partial_view/shop_comments', ['dataProviderComments' => $dataProviderComments,
                     'comment' => $comment, 'profile' => $profile], true); ?>
                <?= $this->render('partial_view/shop_feedback', ['dataProviderFeedback' => $dataProviderFeedback], true); ?>
                <?= $this->render('partial_view/shop_reviews', ['shopFeedbackProvider' => $shopFeedbackProvider, 'shopFeedback' => $shopFeedback], true); ?>
            </div>
        </div>
    <?php else : ?>
        <div class="jumbotron text-center">
            <h2>Вы попробовали присвоить категорию магазину у которого отсутствует профиль!</h2>
        </div>
    <?php endif; ?>
</div>