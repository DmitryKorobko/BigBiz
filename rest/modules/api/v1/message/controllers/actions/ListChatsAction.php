<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\models\message\MessageEntity;
use yii\rest\Action;

/**
 * Class ListChats Action
 *
 * @package rest\modules\api\v1\message\controllers\actions
 */
class ListChatsAction extends Action
{
    /**
     * Action of getting list of chats for user
     *
     * @return array
     */
    public function run()
    {
        /** @var  $model MessageEntity.php */
        $model = new $this->modelClass;

        return $model->getListChats();
    }
}
