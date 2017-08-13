<?php

namespace rest\modules\api\v1\user\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use yii\rest\Action;

/**
 * Class ListThemes Action
 *
 * @package rest\modules\api\v1\user\controllers\actions\theme
 */
class ListThemesAction extends Action
{

    /**
     * Action of getting list of user themes
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var  $theme ThemeEntity.php */
        $theme = new $this->modelClass;

        return $theme->getListUserThemes();
    }
}
