<?php
namespace common\models\message_image;

use yii\db\ActiveRecord;
use Yii;

/**
 * Class MessageImageEntity
 *
 * @package common\models\message_image
 *
 * @property integer $id
 * @property integer $message_id
 * @property string  $src
 * @property int     $sort
 * @property int     $is_upload_to_s3
 * @property integer $created_at
 * @property integer $updated_at
 */
class MessageImageEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_image}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Сообщение',
            'src'        => 'Изображение',
            'sort'       => 'Сортировка',
            'created_at' => 'Дата создания'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['src', 'message_id', 'sort'], 'required'],
            [['comment_id', 'sort', 'is_upload_to_s3'], 'integer'],
            ['src', 'string']
        ];
    }

    /**
     * Method of saving message images for messageId
     *
     * @param $images
     * @param $messageId
     * @return int
     */
    public function saveMessageImages($images, $messageId): int
    {
        $s3 = Yii::$app->get('s3');
        $pattern = Yii::$app->params['s3RegexpPattern'];

        $rows = [];
        foreach ($images as $image) {
            $src = preg_replace($pattern, '', $image['src']);
            $rows[] = [
                'message_id'      => $messageId,
                'src'             => $image['src'],
                'sort'            => (int) $image['sort'],
                'is_upload_to_s3' => $s3->commands()->exist($src)->execute() ? 1 : 0,
                'created_at'      => time(),
                'updated_at'      => time()
            ];
        }

        return Yii::$app->db->createCommand()->batchInsert(self::tableName(),
            ['message_id', 'src', 'sort', 'is_upload_to_s3', 'created_at', 'updated_at'], $rows)->execute();
    }

    /**
     * Method of deleting images from S3
     *
     * @param $images
     */
    public function deleteImagesFromS3($images)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $pattern = Yii::$app->params['s3RegexpPattern'];

        foreach ($images as $image) {
            $src = preg_replace($pattern, '', $image['src']);
            if ($src) {
                $s3->commands()->delete($src)->execute();
            }
        }
    }

    /**
     * Method of deleting images for src
     *
     * @param $images
     * @param $messageId
     * @return int
     */
    public function deleteMessageImages($images, $messageId): int
    {
        $src = [];
        foreach ($images as $image) {
            $src[] = $image['src'];
        }

        return self::deleteAll(['src' => $src, 'message_id' => $messageId]);
    }
}
