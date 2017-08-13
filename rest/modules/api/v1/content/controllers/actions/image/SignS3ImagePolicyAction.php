<?php

namespace rest\modules\api\v1\content\controllers\actions\image;

use Yii;
use yii\rest\Action;
use common\behaviors\ValidatePostParameters;

/**
 * Class SignS3ImagePolicy Action
 *
 * @mixin ValidatePostParameters
 *
 * @package rest\modules\api\v1\content\controllers\actions\image
 */
class SignS3ImagePolicyAction extends Action
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
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['folder', 'fileType']
            ],
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
     * Action of generating pre-signed url of aws s3
     *
     * @return array
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        $postData = Yii::$app->getRequest()->getBodyParams();
        $fileName = $postData['folder'] . '/' . Yii::$app->security->generateRandomString()
                . '.' . $postData['fileType'];
        $bucketName = Yii::$app->params['bucketName'];

        return [
            'url'       => "http://${bucketName}.s3.us-west-2.amazonaws.com/${fileName}",
            'signedUrl' => $s3->commands()->getPresignedUrl($fileName, '+2 days')->execute()
        ];
    }
}
