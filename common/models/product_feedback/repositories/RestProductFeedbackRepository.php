<?php

namespace common\models\product_feedback\repositories;

use common\behaviors\ValidationExceptionFirstMessage;
use common\models\product_feedback\ProductFeedbackentity;
use Yii;
use yii\web\{
    ServerErrorHttpException, NotFoundHttpException, BadRequestHttpException
};

trait RestProductFeedbackRepository
{
    /**
     * Adding a product review action
     *
     * @param $postData
     * @return array
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function addProductReview($postData)
    {
        $postData['user_id'] = Yii::$app->user->identity->getId();

        /** @var  $reviews ProductFeedbackentity */
        $reviews = self::find()
            ->where(['user_id' => $postData['user_id'], 'product_id' => $postData['product_id']])
            ->asArray()
            ->all();
        if ($reviews) {
            throw new BadRequestHttpException('Вы уже оставляли отзыв о данном товаре.');
        }

        $this->setScenario(self::SCENARIO_CREATE);
        $this->setAttributes($postData);

        if ($this->save()) {
            Yii::$app->response->setStatusCode(201, 'Created');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Отзыв успешно добавлен',
                'data'    => $this->getAttributes()
            ];
        } elseif ($this->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($this->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка при добавлении отзыва о товаре.');
    }

    /**
     * Detailed product recall information action
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function getReviewDetail($id)
    {
        $review = self::findOne($id)->toArray();

        if (!$review) {
            throw new NotFoundHttpException('Отзыв не найден.');
        }

        return $review;
    }
}