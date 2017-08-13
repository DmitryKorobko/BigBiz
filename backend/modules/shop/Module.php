<?php
namespace backend\modules\shop;

/**
 * Class Module
 *
 * @package backend\modules\shop
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modules = [
            'control' => [
                'class' => 'backend\modules\shop\control\Module',
            ],
            'home'    => [
                'class' => 'backend\modules\shop\home\Module',
            ],
            'profile' => [
                'class' => 'backend\modules\shop\profile\Module',
            ],
            'support' => [
                'class' => 'backend\modules\shop\support\Module',
            ],
        ];
    }
}