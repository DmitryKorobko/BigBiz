<?php
namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class ImageBehavior
 *
 * @package common\behaviors
 */
class ImageBehavior extends Behavior
{
    /** @var string  */
    public $imagePath = '';

    public $afterDelete = false;

    /** @var string model file field name */
    public $attributeName = '';

    /** @var string path in which image will be saved */
    public $savePath = '';

    /** @var bool save image filename with url or not */
    public $saveWithUrl = false;

    /** @var string url which will be saved with filename if $saveWithUrl === true */
    public $url = '';

    /** @var bool generate a new random name for the filename */
    public $generateNewName = true;

    /** @var bool erase protection the old value of the model attribute if the form returns empty string */
    public $protectOldValue = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT   => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE   => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE   => 'beforeDelete',
            ActiveRecord::EVENT_AFTER_DELETE    => 'afterDelete',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    /**
     * init() get alias for savePath property from himself
     */
    public function init()
    {
        $this->savePath = Yii::getAlias($this->savePath);
    }

    /**
     * EVENT_BEFORE_VALIDATE call beforeValidate() which check new image upload.
     * If true - upload new image
     */
    public function beforeValidate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        if ($file = UploadedFile::getInstance($model, $this->attributeName)) {
            $model->setAttribute($this->attributeName, $file);
        }
    }

    /**
     * EVENT_BEFORE_INSERT call beforeInsert() which in turn call
     * loadFile() method to upload new image
     */
    public function beforeInsert()
    {
        $this->loadFile();
    }

    /**
     * EVENT_BEFORE_UPDATE call beforeUpdate() which check image exist in record,
     * If not then load try to load new image. Also if property protectOldValue === true
     * set attributeName to old value
     */
    public function beforeUpdate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        if ($model->getAttribute($this->attributeName) !== '' &&
            $model->getOldAttribute($this->attributeName) !== $model->getAttribute($this->attributeName)) {
            $this->loadFile();
            return;
        }

        if ($this->protectOldValue) {
            $model->setAttribute(
                $this->attributeName,
                $model->getOldAttribute($this->attributeName)
            );
        }
    }

    /**
     * EVENT_BEFORE_DELETE call beforeDelete which in turn call deleteFile()
     * to remove image from storage
     */
    public function beforeDelete()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$oldFileName = $model->getOldAttribute($this->attributeName)) {
            return;
        }

        $this->imagePath = $oldFileName;

        if (!$this->afterDelete) {
            $this->deleteFile($oldFileName);
        }
    }

    public function afterDelete()
    {
        $this->deleteFile($this->imagePath);
    }

    /**
     * loadFile() upload image on server storage and create set
     * to record attribute new value. Also delete old image from storage.
     */
    protected function loadFile()
    {
        /** @var ActiveRecord $model */
        /** @var UploadedFile $file */
        $model = $this->owner;
        $file = $model->getAttribute($this->attributeName);

        //!Delete the old version
        $this->deleteFile($model->getOldAttribute($this->attributeName));

        if (!($file instanceof UploadedFile)) {
            return;
        }

        $fileName = $file->name;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0755, true);
        }

        if ($this->generateNewName !== false) {
            $fileName = $this->generateFileName($file);
            $file->name = $fileName;
        }

        $file->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);

        if ($this->saveWithUrl && $this->url !== '') {
            $model->setAttributes([$this->attributeName => $this->url . $fileName]);
            return;
        }

        $model->setAttributes([$this->attributeName => $file]);
    }

    /**
     * deleteFile() remove file from storage if it exists
     *
     * @param $fileName
     */
    protected function deleteFile($fileName)
    {
        $oldFileName = str_replace($this->url, '', $fileName);
        $filePath = $this->savePath . DIRECTORY_SEPARATOR . $oldFileName;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Generate new name for uploaded image
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFileName(UploadedFile $file)
    {
        return Yii::$app->security->generateRandomString() . '.' . $file->getExtension();
    }
}