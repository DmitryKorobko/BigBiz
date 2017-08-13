<?php
namespace backend\modules\moderator;

/**
 * Class Module
 *
 * @package backend\modules\moderator
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
                'class' => 'backend\modules\moderator\home\Module',
            ]
        ];
    }
}