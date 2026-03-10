<?php

namespace app\Services\Book;

use Yii;

use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

class Form
{
    public function refund()
    {
        return [];
    }


    public function post()
    {
        return [];
    }


    // Оставить заявку на участие в мероприятии
    public function join($model)
    {
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;


        $model->status_id = 1;

        $image = UploadedFile::getInstance($model, 'image');
        if ($image) {
            $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
        } else {
            $model->image = $isimage;
        }

        $image1 = UploadedFile::getInstance($model, 'image1');
        if ($image1) {
            $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
        } else {
            $model->image1 = $isimage1;
        }

        $image2 = UploadedFile::getInstance($model, 'image2');
        if ($image2) {
            $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
        } else {
            $model->image2 = $isimage2;
        }

        $image3 = UploadedFile::getInstance($model, 'image3');
        if ($image3) {
            $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
        } else {
            $model->image3 = $isimage3;
        }

        $image4 = UploadedFile::getInstance($model, 'image4');
        if ($image4) {
            $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
        } else {
            $model->image4 = $isimage4;
        }

        $image5 = UploadedFile::getInstance($model, 'image5');
        if ($image5) {
            $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
        } else {
            $model->image5 = $isimage5;
        }

        $image6 = UploadedFile::getInstance($model, 'image6');
        if ($image6) {
            $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
        } else {
            $model->image6 = $isimage6;
        }

        $image7 = UploadedFile::getInstance($model, 'image7');
        if ($image7) {
            $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
        } else {
            $model->image7 = $isimage7;
        }

        $image8 = UploadedFile::getInstance($model, 'image8');
        if ($image8) {
            $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
        } else {
            $model->image8 = $isimage8;
        }

        $image9 = UploadedFile::getInstance($model, 'image9');
        if ($image9) {
            $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
        } else {
            $model->image9 = $isimage9;
        }

        if ($model->save()) return $model;

        return [];
    }

    public function createbookingapi()
    {


        return [];
    }


}

