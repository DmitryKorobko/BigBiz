<?php

namespace rest\modules\api\v1\message\controllers\actions;

use common\models\message\MessageEntity;
use yii\rest\Action;
use common\behaviors\ValidateGetParameters;

/**
 * Class ChatHistory Action
 *
 * @mixin ValidateGetParameters
 * @package rest\modules\api\v1\user\controllers\actions
 */
class ChatHistoryAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['recipient_id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action of getting history chat
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var $message MessageEntity.php */
        $message = new $this->modelClass;

        return $message->getChatHistoryByUser(\Yii::$app->request->queryParams['recipient_id']);
    }
}
