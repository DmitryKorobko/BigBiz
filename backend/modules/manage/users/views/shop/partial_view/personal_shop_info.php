<?php
    /* @var $profile \common\models\shop_profile\ShopProfileEntity */
    /* @var $rating  \common\models\shop_feedback\ShopFeedbackEntity */
?>
<div class="col-sm-3">
    <div class="row">
        <div class="col-xs-7 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Фото профиля</h3>
                </div>
                <div class="panel-body">
                    <img alt="Фото магазина"
                         src="<?= \common\models\shop_profile\ShopProfileEntity::getCurrentImage($profile->user_id) ?>"
                         class="img-circle img-responsive">
                    <?php if ($rating && isset($rating['average_rating'])): ?>
                        <div class="container">
                            <div class="row">
                                <h5 class="padding-bottom-7"> Средний рейтинг <?= $rating['average_rating']; ?> <small>/ 5</small>
                                    <input type="number" class="rating" min=0 max=5 data-size="xs" value="<?= $rating['average_rating']; ?>"
                                           readonly data-show-clear="false" data-show-caption="false">
                                </h5>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-xs-5 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Основная информация</h3>
                </div>
                <div class="panel-body">
                    <ul class="profile-details">
                        <li>
                            <div> Название магазина: <b><?php echo $profile->name; ?></b></div>
                        </li>
                        <li>
                            <div>
                                E-mail:
                                <td>
                                    <a href="mailto:<?= @Yii::$app->user->identity->findIdentity($profile->user_id)->username ?>">
                                        <?= @Yii::$app->user->identity->findIdentity($profile->user_id)->username ?>
                                    </a>
                                </td>
                            </div>

                        </li>
                        <li>
                            <div>
                                На форуме с: <b><?= date('F j, Y', @Yii::$app->user->identity->findIdentity($profile->user_id)->created_at); ?></b>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Контактная информация</h3>
                </div>
                <div class="panel-body">
                    <ul class="profile-details">
                        <li>
                            <div><i class="fa fa-clock-o"></i>
                                Время работы: <?= $profile->work_start_time . ' - ' . $profile->work_end_time; ?>
                            </div>
                        </li>
                        <li>
                            <div>
                                <i class="fa fa-map-marker"></i> Города распространения:
                                <?php
                                $cities = \yii\helpers\ArrayHelper::map($profile->cities, 'id', 'name');
                                if (!$cities) {
                                    echo 'Города не выбраны';
                                }
                                foreach ($cities as $city) {
                                    echo $city . ', ';
                                }
                                ?>
                            </div>
                        </li>
                        <li>
                            <div><i class="fa fa-group"></i> Дополнительные контакты:</div>
                            <?php
                            if ($profile->skype) {
                                echo 'Skype     - ' . $profile->skype . '<br>';
                            }
                            if ($profile->viber) {
                                echo 'Viber     - ' . $profile->viber . '<br>';
                            }
                            if ($profile->telegram) {
                                echo 'Telegram  - ' . $profile->telegram . '<br>';
                            }
                            if ($profile->jabber) {
                                echo 'Jabber    - ' . $profile->jabber . '<br>';
                            }
                            if ($profile->vipole) {
                                echo 'Vipole    - ' . $profile->vipole . '<br>';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <?php if ($rating) : ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Рейтинг магазина по категориям</h3>
                    </div>
                    <div class="panel-body">
                        <div class="container">
                            <div class="row">
                                <h5 class="padding-bottom-7">Качество товара <?= $rating['average_product_rating']; ?> <small>/ 5</small>
                                    <input type="number" class="rating" min=0 max=5 data-size="xs" value="<?= $rating['average_product_rating']; ?>"
                                           readonly data-show-clear="false" data-show-caption="false">
                                </h5>
                            </div>
                            <div class="row">
                                <h5 class="padding-bottom-7">Работа оператора <?= $rating['average_operator_rating']; ?> <small>/ 5</small>
                                    <input type="number"  class="rating" min=0 max=5 data-size="xs"
                                           value="<?= $rating['average_operator_rating']; ?>" readonly data-show-clear="false" data-show-caption="false">
                                </h5>
                            </div>
                            <div class="row">
                                <h5 class="padding-bottom-7">Надежность <?= $rating['average_reliability_rating']; ?> <small>/ 5</small>
                                    <input type="number" class="rating" min=0 max=5 data-size="xs" value="<?= $rating['average_reliability_rating']; ?>"
                                           readonly data-show-clear="false" data-show-caption="false">
                                </h5>
                            </div>
                            <div class="row">
                                <h5 class="padding-bottom-7">Качество доставки <?= $rating['average_marker_rating']; ?> <small>/ 5</small>
                                    <input type="number" class="rating" min=0 max=5 data-size="xs" value="<?= $rating['average_marker_rating']; ?>"
                                           readonly data-show-clear="false" data-show-caption="false">
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>