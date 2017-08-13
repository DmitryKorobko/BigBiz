<?php
use common\models\{
    shop_profile\ShopProfileEntity, admin_contact\AdminContactEntity
};
use yii\helpers\Url;
use kartik\switchinput\SwitchInput;
use backend\models\BackendUserEntity;

Yii::$app->formatter->locale = 'ru-RU';
?>

<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="user-panel">
            <div class="pull-left image" style="margin-left: 75px">
                <?php if (Yii::$app->user->can('shop')) : ?>
                    <img  src="<?= ShopProfileEntity::getCurrentImage(@Yii::$app->user->identity->getId()); ?>"
                         class="img-circle" alt="User Image"/>
                <?php elseif (Yii::$app->user->can('admin')) : ?>
                    <img  src="<?= AdminContactEntity::getCurrentImage(@Yii::$app->user->identity->getId()); ?>"
                          class="img-circle" alt="User Image"/>
                <?php else: ?>
                    <img  src="<?= $directoryAsset ?>/img/avatar5.png" class="img-circle" alt="User Image"/>
                <?php endif; ?>
            </div>
            <div class="pull-left info">
                <p>Здравствуйте,
                    <?php if (Yii::$app->user->can('shop')) : ?>
                        <?= ShopProfileEntity::getCurrentName(); ?>
                    <?php else: ?>
                        <?= @Yii::$app->user->identity->username ?>
                    <?php endif; ?>
                </p>

                <div class="status-online-container <?php if (Yii::$app->user->can('shop')) {
                     echo BackendUserEntity::ROLE_SHOP; } else if (Yii::$app->user->can('admin')) {
                     echo BackendUserEntity::ROLE_ADMIN; } else if (Yii::$app->user->can('moder')) {
                     echo BackendUserEntity::ROLE_MODER; } ?>">
                    <?=
                        SwitchInput::widget([
                            'name'          => 'status-online',
                            'options'       => [
                                'id'          => 'status-online',
                                'checked'     => $this->params['statusOnline'],
                            ],
                            'pluginOptions' => [
                                'size'        => 'mini',
                                'onColor'     => 'success',
                                'offColor'    => 'info',
                                'handleWidth' => 70,
                                'onText'      =>'Online',
                                'offText'     =>'Invisible'
                            ]
                        ]);
                    ?>
                </div>

                <?php if (Yii::$app->user->can('shop')) : ?>
                    <?php if ($this->params['status'] === BackendUserEntity::STATUS_VERIFIED) : ?>
                        <br><br><i class="fa fa-check"></i> Верифицирован

                        <?php if ($this->params['categoryEnd'] == null) : ?>
                            <br><br><i class="fa fa-calendar-o"></i> Аккаунт не активен
                        <?php endif; ?>

                        <?php if ($this->params['categoryEnd'] != null && $this->params['categoryEnd'] < time()) : ?>
                            <br><br><i class="fa fa-calendar-times-o"></i> Аккаунт не активен
                        <?php endif; ?>
                        <?php if ($this->params['categoryEnd'] != null && $this->params['categoryStart'] > time()) : ?>
                            <br><br><i class="fa fa-calendar-plus-o"></i> Аккаунт активируется <br>с:
                            <?= Yii::$app->formatter->asDate($this->params['categoryStart'], 'php: d.m.Y') ?>
                        <?php else : ?>
                            <?php if ($this->params['categoryEnd'] != null && $this->params['categoryEnd'] > time()) :?>
                                <br><br><i class="fa fa-calendar-check-o"></i> Аккаунт действителен<br>до:
                                <?= Yii::$app->formatter->asDate($this->params['categoryEnd'], 'php: d.m.Y') ?>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if ($this->params['status'] === BackendUserEntity::STATUS_UNVERIFIED) : ?>
                        <br><br><i class="fa fa-exclamation-circle"></i> Не верифицирован
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <ul class="sidebar-menu">
            <?php if (Yii::$app->user->can('shop')
                && $this->params['status'] === BackendUserEntity::STATUS_VERIFIED) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-archive fa-fw"></i>
                        <span>Баннеры</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= Url::to(['/shop/control/mobile-banner']) ?>">
                                <i class="fa fa-mobile"></i>Баннеры моб. приложения
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/shop/control/website-banner']) ?>">
                                <i class="fa fa-globe"></i>Баннеры сайта
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?= Url::to(['/shop/control/product']) ?>">
                        <i class="fa fa fa-product-hunt fa-fw"></i> <span>Товары</span>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(['/shop/control/theme']) ?>">
                        <i class="fa fa-commenting fa-fw"></i> <span>Темы</span>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?//= Url::to(['/shop/control/comment']) ?><!--">-->
<!--                        <i class="fa fa-envelope fa-fw"></i>-->
<!--                        <span>Комментарии</span>-->
<!--                    </a>-->
<!--                </li>-->
            <?php endif ?>
            <?php if (Yii::$app->user->can('admin')) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bars fa-fw"></i>
                        <span>Категории</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= Url::to(['/manage/content/main-category-section']) ?>">
                                <i class="fa fa-minus fa-fw"></i>
                                <span>Разделов 1 уровня</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/manage/content/child-category-section']) ?>">
                                <i class="fa fa-minus fa-fw"></i>
                                <span>Разделов 2 уровня</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-menu">
                    <a href="<?= Url::to(['/manage/content/theme']) ?>">
                        <i class="fa fa-commenting fa-fw"></i>
                        <span>Темы | Статьи</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-users fa-fw"></i>
                        <span>Пользователи</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= Url::to(['/manage/users/shop']) ?>">
                                <i class="fa fa-shopping-bag"></i>Магазины
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/manage/users/customer']) ?>">
                                <i class="fa fa-user"></i>Покупатели
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/manage/users/administrator']) ?>">
                                <i class="fa fa-user-secret"></i>Администраторы сайта
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/manage/users/moderator']) ?>">
                                <i class="fa fa-vcard"></i>Модераторы сайта
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif ?>
            <?php if (Yii::$app->user->can('moder')) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bars fa-fw"></i>
                        <span>Категории</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= Url::to(['/moderator/content/main-category-section']) ?>">
                                <i class="fa fa-minus fa-fw"></i>
                                <span>Разделов 1 уровня</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/moderator/content/child-category-section']) ?>">
                                <i class="fa fa-minus fa-fw"></i>
                                <span>Разделов 2 уровня</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-menu">
                    <a href="<?= Url::to(['/moderator/content/theme']) ?>">
                        <i class="fa fa-commenting fa-fw"></i>
                        <span>Темы | Статьи</span>
                    </a>
                </li>
            <?php endif ?>
        </ul>
        <ul class="sidebar-menu nav">
            <li class="treeview">
                <?php if ((Yii::$app->user->can('admin')) ||
                (Yii::$app->user->can('shop'))) : ?>
                    <a href="#">
                        <i class="fa fa-cog fa-fw"></i>
                        <span>Настройки</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                <?php endif ?>
                <ul class="treeview-menu">
                    <?php if (Yii::$app->user->can('admin')) : ?>
                        <li>
                            <a href="<?= Url::to(['/manage/settings/common']) ?>">
                                <i class="fa fa-cogs"></i>Общие
                            </a>
                        </li>
                    <?php endif ?>

                    <?php if (Yii::$app->user->can('shop')) : ?>
                        <li>
                            <a href="<?= Url::to(['/shop/profile/shop-profile']) ?>">
                                <i class="fa fa-info"></i>Профиль
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/shop/support/send-message']) ?>">
                                <i class="fa fa-question"></i>Обратная связь
                            </a>
                        </li>
                    <?php endif ?>

                    <?php if (Yii::$app->user->can('admin')) : ?>
                        <li>
                            <a href="<?= Url::to(['/manage/profile/manage-profile']) ?>">
                                <i class="fa fa-info"></i>Профиль
                            </a>
                        </li>
                    <?php endif ?>

                </ul>
            </li>
        </ul>
    </section>
    <?php endif; ?>
</aside>
