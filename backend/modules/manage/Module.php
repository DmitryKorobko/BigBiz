<?php
namespace backend\modules\manage;

/**
 * Class Module
 *
 * @package backend\modules\manage
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
            'home'     => [
                'class' => 'backend\modules\manage\home\Module',
            ],
            'settings' => [
                'class' => 'backend\modules\manage\settings\Module',
            ],
            'users'    => [
                'class' => 'backend\modules\manage\users\Module',
            ],
            'profile' => [
                'class' => 'backend\modules\manage\profile\Module',
            ],
        ];
    }
}