<?php
use yii\{
    widgets\Pjax, grid\GridView, helpers\Html, helpers\ArrayHelper
};
use \common\models\theme\ThemeEntity;

/* @var $dataProviderComments \yii\data\ActiveDataProvider */
/* @var $comment  \common\models\comment\CommentEntity */
/* @var $profile \common\models\shop_profile\ShopProfileEntity */
?>

<div id="comments" class="tab-pane fade">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderComments,
        'filterModel'  => $comment,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'text',
                'filter'    => false
            ],
            [
                'label'     => 'Тема',
                'attribute' => 'theme_id',
                'format'    => 'html',
                'filter'    => Html::activeDropDownList(
                    $comment,
                    'theme_id',
                    ArrayHelper::map(ThemeEntity::find()->asArray()->all(), 'id', 'name'),
                    ['class' => 'form-control', 'value' => Yii::$app->request
                        ->get('CommentEntity')['theme_id'], 'prompt' => '']
                ),
                'value'     => function ($model) {
                    $theme = ThemeEntity::findOne(['id' => $model->theme_id]);
                    return ($theme) ? $theme->name : 'Собственная тема';
                }
            ],
            [
                'attribute' => 'created_at',
                'filter'    => false,
                'format'    => ['date', 'php:F j, Y']
            ],
            [
                'class'         => 'yii\grid\ActionColumn',
                'header'        => 'Действия',
                'template'      => '{delete-comment}',
                'buttons'       => [
                    'delete-comment' => function($url, $model) {
                        return \yii\helpers\Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                            $url,
                            [
                                'title' => 'Удалить комментарий',
                                'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?'
                            ]
                        );
                    }
                ],
                'headerOptions' => [
                    'width' => '100px',
                ]
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
