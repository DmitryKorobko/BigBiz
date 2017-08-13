<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\models\message\MessageEntity;
use yii\rest\Action;

/**
 * Class CountNew Action
 *
 * @package rest\modules\api\v1\message\controllers\actions
 */
class CountNewAction extends Action
{
    /**
     * Action of getting count new private messages by authorized user
     *
     * @return int
     */
    public function run() : int
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var  $message MessageEntity.php */
        $message = new $this->modelClass();

        return $message->getCountNewMessagesByCurrentUser();
    }
}
